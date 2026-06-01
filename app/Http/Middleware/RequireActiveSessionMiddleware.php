<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\DeviceSession;
use Illuminate\Support\Facades\Cache;

class RequireActiveSessionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth('api')->check()) {
            return $next($request);
        }

        $device = $request->attributes->get('current_device');

        if (!$device) {
            return response()->json([
                'status' => 401,
                'message' => 'Device context missing. Please restart the app.'
            ], 401);
        }

        if ($device->trust_level === 'BLOCKED') {
            auth('api')->logout();
            return response()->json([
                'status' => 403,
                'message' => 'Your device has been blocked.',
                'force_logout' => true
            ], 403);
        }

        $payload = auth('api')->payload();
        $sid = $payload->get('sid');

        if (!$sid) {
            auth('api')->logout();
            return response()->json([
                'status' => 401,
                'message' => 'Invalid token structure. Please login again.',
                'force_logout' => true
            ], 401);
        }

        $cachedUserId = Cache::get("session_valid:{$sid}");

        if ($cachedUserId) {
            if ($cachedUserId != auth('api')->id()) {
                auth('api')->logout();
                return response()->json(['status' => 401, 'message' => 'Token ownership mismatch.', 'force_logout' => true], 401);
            }
            return $next($request);
        }

        $session = DeviceSession::where('id', $sid)
            ->whereNull('revoked_at')
            ->where('expires_at', '>', now())
            ->first();

        if (!$session || $session->user_id !== auth('api')->id()) {
            auth('api')->logout();
            return response()->json([
                'status' => 401,
                'message' => 'Your session has expired or been terminated remotely.',
                'force_logout' => true
            ], 401);
        }

        Cache::put("session_valid:{$sid}", $session->user_id, now()->addDays(90));

        return $next($request);
    }
}