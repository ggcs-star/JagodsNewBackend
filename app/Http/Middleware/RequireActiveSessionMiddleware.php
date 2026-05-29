<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

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
                'message' => 'Device context missing.'
            ], 401);
        }

        if ($device->trust_level === 'BLOCKED') {
            return response()->json([
                'status' => 403,
                'message' => 'Your device has been blocked.'
            ], 403);
        }

        return $next($request);
    }
}