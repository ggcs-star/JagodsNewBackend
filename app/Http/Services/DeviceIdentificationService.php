<?php

namespace App\Http\Services;

use App\Models\UserDevice;
use Jenssegers\Agent\Agent;
use App\Jobs\ProcessDeviceLocationJob;

class DeviceIdentificationService
{
    public function processDevice($user, $rawDeviceId, $appVersion, $ip, $userAgent, $language = null)
    {
        $isFallback = false;

        if (!$rawDeviceId) {
            $deviceId = 'fb_' . md5($userAgent . $ip);
            $isFallback = true;
        } else {
            $deviceId = $rawDeviceId;
        }

        $device = UserDevice::firstOrNew([
            'user_id' => $user->id,
            'device_id' => $deviceId,
        ]);

        if ($device->exists && $device->trust_level === 'BLOCKED') {
            return $device;
        }

        $needsDbUpdate = false;
        $ipChanged = $device->last_ip_address !== $ip;

        if (!$device->exists || $device->user_agent !== $userAgent) {
            $agent = new Agent();
            $agent->setUserAgent($userAgent);

            $browser = $agent->browser() ?: 'Unknown';
            $platform = $agent->platform() ?: 'Unknown';
            $deviceName = $agent->device() ?: 'Unknown Device';
            
            $deviceType = 'UNKNOWN';
            if ($agent->isMobile()) $deviceType = 'MOBILE';
            elseif ($agent->isTablet()) $deviceType = 'TABLET';
            elseif ($agent->isDesktop()) $deviceType = 'DESKTOP';
            elseif ($agent->isRobot()) $deviceType = 'BOT';

            $isEmulator = $agent->isRobot(); 

            $rawString = implode('|', [$deviceId, $userAgent, $platform, $appVersion]);
            $fingerprintHash = hash('sha256', $rawString);

            if (!$device->exists) {
                $device->fingerprint_hash = $fingerprintHash;
                $device->device_name = $deviceName;
                $device->browser = $browser;
                $device->platform = $platform;
                $device->app_version = $appVersion;
                
                $device->device_type = $deviceType;
                $device->language = $language;
                $device->is_emulator = $isEmulator;

                $device->trust_level = ($isFallback || $isEmulator) ? 'SUSPICIOUS' : 'NEW';
            } else {
                $riskScore = 0;
                if ($device->platform !== $platform) $riskScore += 40;
                if ($device->browser !== $browser) $riskScore += 20;

                $device->fingerprint_hash = $fingerprintHash;
                $device->browser = $browser;
                $device->platform = $platform;
                $device->device_type = $deviceType; 

                if ($riskScore >= 40 && $device->trust_level !== 'BLOCKED') {
                    $device->trust_level = 'SUSPICIOUS';
                }
            }

            $device->user_agent = $userAgent;
            $needsDbUpdate = true;
        }

        if ($device->app_version !== $appVersion) {
            $device->app_version = $appVersion;
            $needsDbUpdate = true;
        }

        if ($language && $device->language !== $language) {
            $device->language = $language;
            $needsDbUpdate = true;
        }

        $shouldDispatchJob = false;

        if ($ipChanged || !$device->exists) {
            $device->last_ip_address = $ip;
            $needsDbUpdate = true;
            $shouldDispatchJob = true;
        }

        if (!$device->last_active_at || $device->last_active_at->diffInMinutes(now()) >= 5) {
            $device->last_active_at = now();
            $needsDbUpdate = true;
        }

        if ($needsDbUpdate) {
            $device->save();
        }

        if ($shouldDispatchJob && $device->id) {
            ProcessDeviceLocationJob::dispatch($device->id, $ip);
        }

        return $device;
    }
}