<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceSession;
use Illuminate\Support\Str;

class RefreshTokenController extends Controller
{
    public function refresh(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $session = DeviceSession::with('user', 'device')
            ->where('refresh_token', hash('sha256', $request->refresh_token))
            ->first();

        if (!$session) {
            return response()->json([
                'status' => 401,
                'message' => 'Invalid refresh token. Please login again.'
            ], 401);
        }

        if ($session->revoked_at !== null) {
            DeviceSession::where('user_device_id', $session->user_device_id)->update(['revoked_at' => now()]);
            return response()->json([
                'status' => 401,
                'message' => 'Security alert: Token reuse detected. All sessions revoked. Please login again.'
            ], 401);
        }

        $currentDeviceId = $request->header('X-Device-ID');
        if ($currentDeviceId && $session->device->device_id !== $currentDeviceId) {
            $session->update(['revoked_at' => now()]);
            return response()->json([
                'status' => 403,
                'message' => 'Device verification failed. Token revoked.'
            ], 403);
        }

        if ($session->expires_at < now()) {
            $session->update(['revoked_at' => now()]); 
            return response()->json([
                'status' => 401,
                'message' => 'Session expired. Please login again.'
            ], 401);
        }

        if ($session->device && $session->device->trust_level === 'BLOCKED') {
            $session->update(['revoked_at' => now()]);
            return response()->json([
                'status' => 403,
                'message' => 'This device has been blocked.'
            ], 403);
        }

        $newRawRefreshToken = Str::random(64);
        
        $session->update([
            'revoked_at' => now(),
            'last_used_at' => now(),
        ]);

        DeviceSession::create([
            'user_id' => $session->user_id,
            'user_device_id' => $session->user_device_id,
            'refresh_token' => hash('sha256', $newRawRefreshToken),
            'ip_address' => $request->ip(), 
            'expires_at' => now()->addDays(90),
        ]);

        $newAccessToken = auth('api')->login($session->user);

        return response()->json([
            'data' => [
                'token' => $newAccessToken,
                'refresh_token' => $newRawRefreshToken, 
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ],
            'message' => 'Tokens refreshed successfully.',
            'status' => 200
        ]);
    }
}