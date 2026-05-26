<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireTrustedDevice
{
    public function handle(Request $request, Closure $next)
    {
        $device = $request->attributes->get('current_device');

        if (!$device) {
            return response()->json(['error' => 'Device context missing.'], 500);
        }

        if (in_array($device->trust_level, ['NEW', 'SUSPICIOUS'])) {
            return response()->json([
                'error' => 'Unrecognized or suspicious device. Please verify your identity.',
                'action_required' => 'DEVICE_VERIFICATION_REQUIRED', 
                'device_id' => $device->id
            ], 403);
        }

        if ($device->trust_level === 'BLOCKED') {
            return response()->json(['error' => 'This device is permanently blocked.'], 403);
        }

        return $next($request);
    }
}