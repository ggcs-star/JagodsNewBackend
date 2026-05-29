<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use App\Notifications\OneTimePasswordSend;
use Exception;
use Illuminate\Support\Facades\Log;

class OtpService
{
    const OTP_EXPIRY_MINUTES = 5;

    public function generateAndSend($user, $purpose, $deviceId, $ip)
    {

        $identifier = $user->email ?: ($user->phone ?: $deviceId);
        $rateLimitKey = "send_otp_{$purpose}_" . md5($identifier);

        if (RateLimiter::tooManyAttempts($rateLimitKey, 1)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return ['status' => false, 'code' => 429, 'message' => "Too many attempts. Wait {$seconds} seconds."];
        }

        $otpCode = rand(100000, 999999);

        $cacheKey = $this->buildOtpCacheKey(
            $user->id,
            $purpose,
            $deviceId,
            $ip
        );
        Cache::put($cacheKey, $otpCode, now()->addMinutes(self::OTP_EXPIRY_MINUTES));

        try {
            $user->notify(new OneTimePasswordSend($otpCode));

            RateLimiter::hit($rateLimitKey, 60);

        } catch (Exception $e) {
            Log::error("Universal OTP Failed", ['error' => $e->getMessage()]);
            return ['status' => false, 'code' => 500, 'message' => 'Failed to send OTP. Try again later.'];
        }

        return [
            'status' => true,
            'code' => 200,
            'message' => 'OTP sent successfully.',
            'expires_in' => self::OTP_EXPIRY_MINUTES
        ];
    }


    public function verify($user, $purpose, $otpInput, $deviceId, $ip)
    {
        $cacheKey = $this->buildOtpCacheKey(
            $user->id,
            $purpose,
            $deviceId,
            $ip
        );

        $cachedOtp = Cache::get($cacheKey);

        if (!$cachedOtp || $cachedOtp != $otpInput) {
            return ['status' => false, 'message' => 'Invalid or expired OTP.'];
        }

        Cache::forget($cacheKey);

        return ['status' => true, 'message' => 'OTP verified.'];
    }

    private function buildOtpCacheKey(
        $userId,
        $purpose,
        $deviceId,
        $ip
    ): string {

        return sprintf(
            'otp:%s:%s:%s:%s',
            $userId,
            sha1($deviceId),
            sha1($ip),
            $purpose
        );
    }
}