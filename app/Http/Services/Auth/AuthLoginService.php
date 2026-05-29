<?php

namespace App\Http\Services\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Models\DeviceSession;
use App\Enums\UserStatus;
use App\Enums\UserRole;
use App\Http\Resources\v1\RestaurantResource;
use App\Http\Services\Security\SecurityRiskService;
use App\Http\Services\DeviceIdentificationService;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Http\Services\OtpService;

class AuthLoginService
{
    protected $deviceService;
    protected $riskService;
    protected $otpService;

    public function __construct(
        DeviceIdentificationService $deviceService,
        SecurityRiskService $riskService,
        OtpService $otpService
    ) {
        $this->deviceService = $deviceService;
        $this->riskService = $riskService;
        $this->otpService = $otpService;
    }

    public function login(array $credentials, $request, $requestedRole = null): array
    {
        if (!auth('api')->validate($credentials)) {
            return ['status' => false, 'code' => 401, 'message' => 'Invalid credentials.'];
        }

        $userField = isset($credentials['email']) ? 'email' : 'phone';
        $user = User::where($userField, $credentials[$userField])->first();

        if (!$user) {
            return ['status' => false, 'code' => 404, 'message' => 'User not found.'];
        }

        if ($user->status == UserStatus::INACTIVE) {
            return ['status' => false, 'code' => 403, 'message' => 'Your account is inactive.'];
        }

        if ($requestedRole && ($requestedRole != $user->myrole)) {
            return ['status' => false, 'code' => 403, 'message' => "You don't have permission to login to this portal."];
        }

        $device = $this->deviceService->processDevice(
            $user,
            $request->header('X-Device-ID'),
            $request->header('X-App-Version', '1.0.0'),
            $request->ip(),
            $request->userAgent(),
            $request->header('Accept-Language')
        );

        $riskAnalysis = $this->riskService->analyzeRisk($device, $user, $request->ip());

        if ($riskAnalysis['action'] === 'BLOCK') {
            $device->update([
                'trust_level' => 'BLOCKED',
                'blocked_at' => now(),
            ]);

            return [
                'status' => false,
                'code' => 403,
                'message' => 'Access blocked due to high security risk.',
                'risk' => $riskAnalysis
            ];
        }

        if ($riskAnalysis['action'] === 'REQUIRE_OTP') {

            $otpResult = $this->otpService->generateAndSend(
                $user,
                'device_verification',
                $request->header('X-Device-ID'),
                $request->ip()
            );

            if (!$otpResult['status']) {
                return [
                    'status' => false,
                    'code' => $otpResult['code'] ?? 400,
                    'message' => $otpResult['message'] 
                ];
            }

            return [
                'status' => false,
                'code' => 401,
                'requires_otp' => true,
                'message' => 'Suspicious login detected. OTP sent to your registered contact.',
                'risk' => $riskAnalysis,
                'device_id' => $device->id
            ];
        }
        return $this->generateTokensAndSession($user, $device, $request, $requestedRole);
    }

    public function otpLogin(User $user, $device, $request, $requestedRole = null): array
    {
        if ($requestedRole && ($requestedRole != $user->myrole)) {
            return ['status' => false, 'code' => 403, 'message' => "Permission denied."];
        }

        if ($device) {
            $device->update([
                'trust_level' => 'VERIFIED',
                'failed_attempts' => 0
            ]);
        }

        return $this->generateTokensAndSession($user, $device, $request, $requestedRole);
    }

    private function generateTokensAndSession($user, $device, $request, $role): array
    {
        $token = auth('api')->login($user);
        $refreshToken = Str::random(64);

        try {
            DB::beginTransaction();

            if ($device) {
                $device->increment('login_count');
                $device->update([
                    'last_login_at' => now(),
                    'last_active_at' => now(),
                    'is_current' => true,
                ]);

                DeviceSession::where('user_device_id', $device->id)
                    ->whereNull('revoked_at')
                    ->update(['revoked_at' => now()]);

                DeviceSession::create([
                    'user_id' => $user->id,
                    'user_device_id' => $device->id,
                    'refresh_token' => hash('sha256', $refreshToken),
                    'ip_address' => $request->ip(),
                    'expires_at' => now()->addDays(90),
                ]);
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
            auth('api')->logout();
            return ['status' => false, 'code' => 500, 'message' => 'Internal server error while creating session.'];
        }

        $restaurant = [];
        $waiterId = 0;

        if ($role == UserRole::WAITER && $user->waiter) {
            $restaurant = !blank($user->waiter->restaurant) ? new RestaurantResource($user->waiter->restaurant) : [];
            $waiterId = $user->waiter->id;
        } else {
            $restaurant = !blank($user->restaurant) ? new RestaurantResource($user->restaurant) : [];
        }

        return [
            'status' => true,
            'token' => $token,
            'refresh_token' => $refreshToken,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user,
            'device' => $device,
            'restaurant_data' => $restaurant,
            'waiter_id_data' => $waiterId,
        ];
    }
}