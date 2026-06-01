<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\OtpService;
use App\Models\User;
use App\Http\Services\Auth\AuthLoginService;
use App\Enums\UserRole;
use App\Http\Resources\v1\RestaurantResource;
use App\Http\Resources\v1\PrivateUserResource;
use App\Http\Services\DeviceIdentificationService;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
class UniversalOtpController extends Controller
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

    public function send(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required',
            'purpose' => 'required|in:login,device_verification,forgot_password'
        ]);

        $user = $this->findUser($request->email_or_phone);
        if (!$user) {
            return response()->json(['status' => 404, 'message' => 'User not found.'], 404);
        }

        $result = $this->otpService->generateAndSend(
            $user,
            $request->purpose,
            $request->header('X-Device-ID'),
            $request->ip()
        );

        return response()->json([
            'message' => $result['message'],
            'expires_in' => $result['expires_in'] ?? null
        ], $result['code']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required',
            'otp' => 'required|numeric',
            'purpose' => 'required|in:login,device_verification,forgot_password,profile_update',
        ]);

        $user = $this->findUser($request->email_or_phone);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $verification = $this->otpService->verify(
            $user,
            $request->purpose,
            $request->otp,
            $request->header('X-Device-ID'),
            $request->ip()
        );

        if (!$verification['status']) {
            return response()->json(['status' => 400, 'message' => $verification['message']], 400);
        }

        switch ($request->purpose) {
            case 'login':
            case 'device_verification':
                return $this->handleSuccessfulLogin($user, $request);

            case 'forgot_password':
                return response()->json([
                    'status' => 200,
                    'message' => 'OTP verified. Please set new password.',
                    'reset_token' => 'xyz...'
                ]);
            case 'profile_update':
                return $this->handleProfileUpdate($user, $request);
        }
    }

    private function findUser($input)
    {
        $field = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $input = $field === 'phone' ? preg_replace('/[^0-9]/', '', $input) : trim($input);
        return User::where($field, $input)->first();
    }

  private function handleSuccessfulLogin($user, $request)
    {
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

        $device = $this->deviceService->processDevice(
            $user,
            $finalDeviceId,
            $request->header('X-App-Version', '1.0.0'),
            $ip,
            $userAgent,
            $language,
            $agent 
        );

        $role = $request->role;

        $response = $this->authLoginService->otpLogin($user, $device, $request, $role);

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Successfully verified and logged in.',
            'data' => new PrivateUserResource($response['user']), 
            'token' => $response['token'],
            'refresh_token' => $response['refresh_token'],
            'expires_in' => $response['expires_in'],
            'restaurant' => $response['restaurant_data'],
            'waiter_id' => $response['waiter_id_data'],
        ], 200);
    }

   
    private function handleProfileUpdate($user, $request)
    {
        $request->validate([
            'temp_token' => 'required|string'
        ]);

        $cacheKey = "profile_update_" . $request->temp_token;
        $updateData = Cache::get($cacheKey);

        if (!$updateData) {
            return response()->json(['status' => 400, 'message' => 'Update session expired. Please try again.'], 400);
        }

        if ($updateData['device_id'] !== $request->header('X-Device-ID')) {
            return response()->json(['status' => 403, 'message' => 'Security mismatch. Update blocked.'], 403);
        }

        $user->first_name = $updateData['first_name'];
        $user->last_name = $updateData['last_name'];
        $user->email = $updateData['email'];
        $user->phone = $updateData['phone'];
        $user->address = $updateData['address'];
        $user->username = $updateData['username'];
        $user->save();
      
        if (!empty($updateData['address'])) {
            
            \App\Models\Address::updateOrCreate(
                ['user_id' => $user->id, 'label' => \App\Enums\AddressType::HOME],
                ['address' => $updateData['address'], 'label_name' => trans('address_types.' . \App\Enums\AddressType::HOME)]
            );
        }

        Cache::forget($cacheKey);

        return response()->json([
            'status' => 200,
            'message' => 'Profile updated successfully!',
            'data' => new PrivateUserResource($user)
        ], 200);
    }
}