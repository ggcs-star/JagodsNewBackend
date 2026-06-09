<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Services\SecurityLogger;
use App\Notifications\OneTimePasswordSend;
use Exception;

class DeviceVerificationService
{
    const MAX_FAILED_ATTEMPTS = 5;
    const OTP_EXPIRY_MINUTES = 10;
    const MAX_OTP_REQUESTS_PER_MINUTE = 3;

    private function getCacheKey($userId, $deviceId)
    {
        return "device_verification_{$userId}_{$deviceId}";
    }

    public function sendOtp($user, $device)
    {
        if ($device->trust_level === 'TRUSTED') {
            return ['success' => true, 'message' => 'Device is already trusted.', 'code' => 200];
        }

        $rateLimitKey = 'send_otp_' . $user->id;

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_OTP_REQUESTS_PER_MINUTE)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return [
                'success' => false,
                'error' => "Too many requests. Please try again in {$seconds} seconds.",
                'code' => 429
            ];
        }

        RateLimiter::hit($rateLimitKey, 60);

        $otp = rand(100000, 999999);
        $cacheKey = $this->getCacheKey($user->id, $device->id);

        Cache::put($cacheKey, $otp, now()->addMinutes(self::OTP_EXPIRY_MINUTES));

        try {
            $user->notify(new OneTimePasswordSend($otp));
        } catch (Exception $e) {
            Log::error("OTP Sending Failed", ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'error' => 'Failed to send verification code. Please check your system settings or try again later.',
                'code' => 500
            ];
        }

        Log::info("Device verification OTP generated", [
            'user_id' => $user->id,
            'device_id' => $device->id,
        ]);

        return [
            'success' => true,
            'message' => 'Verification code sent successfully.',
            'expires_in_minutes' => self::OTP_EXPIRY_MINUTES,
            'code' => 200
        ];
    }

    public function verifyOtp($user, $device, $otpInput, $request)
    {
        $cacheKey = $this->getCacheKey($user->id, $device->id);
        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || $cachedOtp != $otpInput) {
            $device->increment('failed_attempts');

            SecurityLogger::log($request, 'SECURITY', 'otp_failed', 8, 400, [
                'device_id' => $device->id
            ]);

            if ($device->failed_attempts >= self::MAX_FAILED_ATTEMPTS) {
                $device->update(['trust_level' => 'BLOCKED']);
                Cache::forget($cacheKey);

                SecurityLogger::log($request, 'SECURITY', 'device_blocked', 10, 403, [
                    'device_id' => $device->id,
                    'failed_attempts' => $device->failed_attempts
                ]);

                return [
                    'success' => false,
                    'error' => 'Too many failed attempts. Device blocked permanently.',
                    'code' => 403
                ];
            }

            $attemptsLeft = self::MAX_FAILED_ATTEMPTS - $device->failed_attempts;

            return [
                'success' => false,
                'error' => "Invalid or expired OTP. You have {$attemptsLeft} attempts left.",
                'code' => 400
            ];
        }

        $device->update([
            'trust_level' => 'TRUSTED',
            'failed_attempts' => 0
        ]);

        Cache::forget($cacheKey);
        RateLimiter::clear('send_otp_' . $user->id);

        SecurityLogger::log($request, 'AUTH', 'device_trusted', 1, 200, [
            'device_id' => $device->id,
            'user_id' => $user->id
        ]);

        Log::info("Device marked as TRUSTED", [
            'user_id' => $user->id,
            'device_id' => $device->id
        ]);

        return [
            'success' => true,
            'message' => 'Device verified successfully.',
            'code' => 200
        ];
    }
}