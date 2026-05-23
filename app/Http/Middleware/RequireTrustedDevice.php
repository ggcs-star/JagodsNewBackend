<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireTrustedDevice
{
    public function handle(Request $request, Closure $next)
    {
        // Ye object pehle wale DeviceIdentificationMiddleware ne yahan inject kiya tha
        $device = $request->attributes->get('current_device');

        if (!$device) {
            return response()->json(['error' => 'Device context missing.'], 500);
        }

        // Agar device NEW ya SUSPICIOUS hai, toh critical action rok do!
        if (in_array($device->trust_level, ['NEW', 'SUSPICIOUS'])) {
            return response()->json([
                'error' => 'Unrecognized or suspicious device. Please verify your identity.',
                'action_required' => 'DEVICE_VERIFICATION_REQUIRED', // Frontend is code ko padh kar OTP popup dikhayega
                'device_id' => $device->id
            ], 403);
        }

        // Agar BLOCKED hai, toh seedha bahar nikalo
        if ($device->trust_level === 'BLOCKED') {
            return response()->json(['error' => 'This device is permanently blocked.'], 403);
        }

        // Agar TRUSTED hai, toh request ko aage jaane do (e.g., Controller tak)
        return $next($request);
    }
}