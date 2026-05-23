<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Http\Services\SecurityLogger;

class DeviceVerificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendOtp(Request $request)
    {
        $user = auth()->user();
        $device = $request->attributes->get('current_device');

        if ($device->trust_level === 'TRUSTED') {
            return response()->json([
                'message' => 'Device is already trusted.'
            ], 200);
        }

        $otp = rand(100000, 999999);

        $cacheKey = "device_verification_{$user->id}_{$device->id}";

        Cache::put($cacheKey, $otp, now()->addMinutes(10));

        Log::info("Device verification OTP generated", [
            'user_id' => $user->id,
            'device_id' => $device->id,
            'otp' => $otp
        ]);

        return response()->json([
            'message' => 'Verification code sent successfully.',
            'expires_in_minutes' => 10
        ], 200);
    }



    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = auth()->user();
        $device = $request->attributes->get('current_device');

        $cacheKey = "device_verification_{$user->id}_{$device->id}";
        $cachedOtp = Cache::get($cacheKey);


        if (!$cachedOtp || $cachedOtp != $request->otp) {

            $device->increment('failed_attempts');

            SecurityLogger::log(
                $request,
                'SECURITY',
                'otp_failed',
                8,
                400,
                [
                    'provided_otp' => $request->otp,
                    'device_id' => $device->id
                ]
            );

            if ($device->failed_attempts >= 5) {

                $device->update([
                    'trust_level' => 'BLOCKED'
                ]);

                Cache::forget($cacheKey);

                SecurityLogger::log(
                    $request,
                    'SECURITY',
                    'device_blocked',
                    10,
                    403,
                    [
                        'device_id' => $device->id,
                        'failed_attempts' => $device->failed_attempts
                    ]
                );

                return response()->json([
                    'error' => 'Too many failed attempts. Device blocked.'
                ], 403);
            }

            return response()->json([
                'error' => 'Invalid or expired OTP.'
            ], 400);
        }

        $device->update([
            'trust_level' => 'TRUSTED',
            'failed_attempts' => 0
        ]);

        Cache::forget($cacheKey);

        SecurityLogger::log(
            $request,
            'AUTH',
            'device_trusted',
            1,
            200,
            [
                'device_id' => $device->id,
                'user_id' => $user->id
            ]
        );

        Log::info("Device marked as TRUSTED", [
            'user_id' => $user->id,
            'device_id' => $device->id
        ]);

        return response()->json([
            'message' => 'Device verified successfully.'
        ], 200);
    }
}