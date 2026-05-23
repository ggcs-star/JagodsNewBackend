<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;
use App\Jobs\ProcessDeviceLocationJob;
class DeviceIdentificationMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        $rawDeviceId = $request->header('X-Device-ID');
        $appVersion = $request->header('X-App-Version', '1.0.0');
        $isFallback = false;

        if (!$rawDeviceId) {
            $deviceId = 'fb_' . md5($request->userAgent() . $request->ip());
            $isFallback = true;
        } else {
            $deviceId = $rawDeviceId;
        }

        $agent = new \Jenssegers\Agent\Agent();
        $browser = $agent->browser() ?: 'Unknown';
        $platform = $agent->platform() ?: 'Unknown';
        $deviceName = $agent->device() ?: 'Unknown Device';

        // 🚨 FIX: Generate the Fingerprint Hash properly
        $rawString = implode('|', [$deviceId, $request->userAgent(), $platform, $appVersion, $request->ip()]);
        $fingerprintHash = hash('sha256', $rawString);

        $device = \App\Models\UserDevice::firstOrNew([
            'user_id' => $user->id,
            'device_id' => $deviceId,
        ]);

        if ($device->exists && $device->trust_level === 'BLOCKED') {
            return response()->json(['error' => 'Device blocked due to policy violations.'], 403);
        }

        if (!$device->exists) {
            // 🚨 FIX: Save the Hash to Database
            $device->fingerprint_hash = $fingerprintHash;

            $device->device_name = $deviceName;
            $device->browser = $browser;
            $device->platform = $platform;
            $device->app_version = $appVersion;
            $device->trust_level = $isFallback ? 'SUSPICIOUS' : 'NEW';
        } else {
            $riskScore = 0;
            if ($device->platform !== $platform)
                $riskScore += 40;
            if ($device->browser !== $browser)
                $riskScore += 20;

            if ($device->app_version !== $appVersion) {
                $riskScore += 5;
                $device->app_version = $appVersion;
            }

            // 🚨 FIX: Update hash if it legitimately changed
            $device->fingerprint_hash = $fingerprintHash;

            if ($riskScore >= 40 && $device->trust_level !== 'BLOCKED') {
                $device->trust_level = 'SUSPICIOUS';
            }
        }

        $device->last_ip_address = $request->ip();
        $device->user_agent = $request->userAgent();
        $device->last_active_at = now();
        $device->save();
        ProcessDeviceLocationJob::dispatch($device->id, $request->ip());
        $request->attributes->set('current_device', $device);

        return $next($request);
    }
}