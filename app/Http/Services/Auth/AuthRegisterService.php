<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use App\Models\DeliveryBoyAccount;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Http\Services\OtpService;
use App\Http\Services\DeviceIdentificationService;
use App\Enums\UserStatus;
use Exception;
use Jenssegers\Agent\Agent;
class AuthRegisterService
{
    protected $otpService;
    protected $authLoginService;
    protected $deviceService;

    public function __construct(
        OtpService $otpService,
        AuthLoginService $authLoginService,
        DeviceIdentificationService $deviceService
    ) {
        $this->otpService = $otpService;
        $this->authLoginService = $authLoginService;
        $this->deviceService = $deviceService;
    }


    public function processRegistrationOtp(array $requestData, string $deviceId, string $ip): array
    {
        $tempToken = Str::uuid()->toString();

        $userData = [
            'name' => $requestData['name'] ?? '',
            'email' => $requestData['email'] ?? null,
            'phone' => $requestData['phone'] ?? null,
            'role' => $requestData['role'],
            'password_hash' => bcrypt($requestData['password']),
            'device_id' => $deviceId,
            'ip_address' => $ip,
        ];

        $tempUser = new User();
        $tempUser->email = $userData['email'];
        $tempUser->phone = $userData['phone'];
        $tempUser->id = $tempToken;

        $result = $this->otpService->generateAndSend(
            $tempUser,
            'registration',
            $deviceId,
            $ip
        );

        if (!$result['status']) {
            return [
                'status' => false,
                'code' => $result['code'] ?? 400,
                'message' => $result['message'] ?? 'Failed to send OTP. Please try again.'
            ];
        }

        $cacheKey = "reg_data_" . $tempToken;
        Cache::put($cacheKey, $userData, now()->addMinutes(10));

        return [
            'status' => true,
            'code' => 200,
            'message' => 'If the details are valid, an OTP has been sent. Please verify.',
            'temp_token' => $tempToken,
            'expires_in' => 10
        ];
    }

  public function verifyAndRegister($request, string $tempToken, string $otp, string $deviceId): array
    {
        $rateLimitKey = "verify_reg_" . $tempToken;
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            return ['status' => false, 'code' => 429, 'message' => 'Too many failed attempts. Please try again after 15 minutes.'];
        }

        $cacheKey = "reg_data_" . $tempToken;
        $userData = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (!$userData) {
            return ['status' => false, 'code' => 400, 'message' => 'Registration session expired. Please sign up again.'];
        }

        if ($userData['device_id'] !== $deviceId) {
            return ['status' => false, 'code' => 403, 'message' => 'Device mismatch detected. Registration blocked for security.'];
        }

        $tempUser = new \App\Models\User();
        $tempUser->id = $tempToken;

        $verification = $this->otpService->verify(
            $tempUser,
            'registration',
            $otp,
            $deviceId,
            $request->ip()
        );

        if (!$verification['status']) {
            \Illuminate\Support\Facades\RateLimiter::hit($rateLimitKey, 900);
            return ['status' => false, 'code' => 400, 'message' => $verification['message']];
        }

        \Illuminate\Support\Facades\RateLimiter::clear($rateLimitKey);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            if (\App\Models\User::where('email', $userData['email'])->orWhere('phone', $userData['phone'])->exists()) {
                throw new \Exception('Account already exists with this email or phone.');
            }

            $first_name = '';
            $last_name = '';
            if (!empty($userData['name'])) {
                $parts = $this->split_name($userData['name']);
                $first_name = $parts[0];
                $last_name = $parts[1] ?? '';
            }

            $username = !empty($userData['email']) ? $this->username($userData['email']) : '';

            $mainuser = \App\Models\User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $userData['email'],
                'username' => $username,
                'phone' => $userData['phone'],
                'password' => $userData['password_hash'],
                'status' => \App\Enums\UserStatus::ACTIVE
            ]);

            $role = \Spatie\Permission\Models\Role::find($userData['role']);
            if ($role) {
                $mainuser->assignRole($role->name);
            }

            if ($userData['role'] == 4) {
                \App\Models\DeliveryBoyAccount::create([
                    'user_id' => $mainuser->id,
                    'delivery_charge' => 0,
                    'balance' => 0
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return ['status' => false, 'code' => 400, 'message' => $e->getMessage()];
        }

        \Illuminate\Support\Facades\Cache::forget($cacheKey);

        // 🚨 YAHAN FIX KIYA GAYA HAI - Agent aur Fallback ID Generate karke pass karna
        $userAgent = $request->userAgent();
        $language = $request->header('Accept-Language');
        $ip = $request->ip();
        
        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($userAgent);

        $rawDeviceId = $request->header('X-Device-ID');
        if (empty($rawDeviceId)) {
            $finalDeviceId = 'fb_' . hash('sha256', $userAgent . $language . $ip);
        } else {
            $finalDeviceId = $rawDeviceId;
        }

        // 🚨 Ab 7 arguments pass ho rahe hain properly
        $device = $this->deviceService->processDevice(
            $mainuser,
            $finalDeviceId,
            $request->header('X-App-Version', '1.0.0'),
            $ip,
            $userAgent,
            $language,
            $agent // 7th Argument!
        );

        $loginResponse = $this->authLoginService->otpLogin($mainuser, $device, $request, $userData['role']);

        if (!$loginResponse['status']) {
            return ['status' => false, 'code' => $loginResponse['code'], 'message' => $loginResponse['message']];
        }

        return [
            'status' => true,
            'user' => $mainuser,
            'login_data' => $loginResponse
        ];
    }

    private function split_name($name)
    {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#' . $last_name . '#', '', $name));
        return [$first_name, $last_name];
    }

    private function username($email)
    {
        $emails = explode('@', $email);
        return $emails[0] . mt_rand();
    }
   
    public function resendRegistrationOtp(string $tempToken, string $deviceId, string $ip): array
    {
        $cacheKey = "reg_data_" . $tempToken;
        $userData = Cache::get($cacheKey);

        if (!$userData) {
            return [
                'status' => false, 
                'code' => 400, 
                'message' => 'Session expired. Please fill the registration form again.'
            ];
        }

        if ($userData['device_id'] !== $deviceId) {
            return [
                'status' => false, 
                'code' => 403, 
                'message' => 'Device mismatch. Action blocked for security.'
            ];
        }

        Cache::put($cacheKey, $userData, now()->addMinutes(10));

        $tempUser = new User();
        $tempUser->email = $userData['email'];
        $tempUser->phone = $userData['phone'];
        $tempUser->id = $tempToken;

        $result = $this->otpService->generateAndSend(
            $tempUser,
            'registration',
            $deviceId,
            $ip
        );

        if (!$result['status']) {
            return [
                'status' => false,
                'code' => $result['code'] ?? 400,
                'message' => $result['message']
            ];
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'OTP has been resent successfully.',
            'expires_in' => 5
        ];
    }
}