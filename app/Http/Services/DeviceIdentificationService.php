<?php

namespace App\Http\Services;

use App\Models\UserDevice;
use Jenssegers\Agent\Agent;
use App\Jobs\ProcessDeviceLocationJob;

class DeviceIdentificationService
{
    public function processDevice($user, $rawDeviceId, $appVersion, $ip, $userAgent, $language = null)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        
        $browser = $agent->browser() ?: 'Unknown';
        $platform = $agent->platform() ?: 'Unknown';

        // 🚨 FIX 1: IP Hataya Fallback se! Ab Wi-Fi se 5G jane par device naya nahi banega.
        $deviceId = $rawDeviceId ?: 'fb_' . md5($platform . $browser . $language);
        $isFallback = empty($rawDeviceId);

        $userId = ($user && isset($user->id) && is_numeric($user->id)) ? $user->id : null;

        if (!$userId) {
            return $this->buildTempDevice($deviceId, $userAgent, $appVersion, $ip, $isFallback, $language, $agent);
        }

        $device = UserDevice::firstOrNew([
            'user_id' => $userId,
            'device_id' => $deviceId,
        ]);

        if ($device->exists && $device->trust_level === 'BLOCKED') {
            return $device;
        }

        $needsDbUpdate = false;
        $ipChanged = $device->last_ip_address !== $ip;

        // 🚨 FIX 2: Existing Device Update me bhi Device Name update karein
        $this->updateDeviceCharacteristics($device, $userAgent, $deviceId, $isFallback, $agent);
        $needsDbUpdate = true;

        if ($device->app_version !== $appVersion) {
            $device->app_version = $appVersion;
            $needsDbUpdate = true;
        }

        if ($language && $device->language !== $language) {
            $device->language = $language;
            $needsDbUpdate = true;
        }

        // 🚨 FIX 3: Progressive Trust Upgrade (Verified -> Trusted after 3 logins)
        if ($device->exists && $device->trust_level === 'VERIFIED' && $device->login_count >= 3) {
            $device->trust_level = 'TRUSTED';
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

    private function updateDeviceCharacteristics($device, $userAgent, $deviceId, $isFallback, Agent $agent)
    {
        $browser = $agent->browser() ?: 'Unknown';
        $platform = $agent->platform() ?: 'Unknown';
        $deviceName = $agent->device() ?: 'Unknown Device';

        // 🚨 FIX 4: Bot vs Emulator Differentiation
        $isBot = $this->isBot($userAgent);
        $isEmulator = $this->isEmulator($userAgent) || $agent->isRobot();

        $deviceType = 'UNKNOWN';
        if ($isBot) $deviceType = 'BOT';
        elseif ($isEmulator) $deviceType = 'EMULATOR';
        elseif ($agent->isMobile()) $deviceType = 'MOBILE';
        elseif ($agent->isTablet()) $deviceType = 'TABLET';
        elseif ($agent->isDesktop()) $deviceType = 'DESKTOP';

        // 🚨 FIX 5: Stable Fingerprint (No App Version, No Raw UserAgent)
        $fingerprintHash = hash('sha256', $deviceId . '|' . $browser . '|' . $platform);

        if (!$device->exists) {
            $device->fingerprint_hash = $fingerprintHash;
            $device->device_name = $deviceName;
            $device->browser = $browser;
            $device->platform = $platform;
            $device->device_type = $deviceType;
            $device->is_emulator = ($isEmulator || $isBot);
            $device->trust_level = ($isFallback || $isBot || $isEmulator) ? 'SUSPICIOUS' : 'NEW';
        } else {
            // Check for drastic changes
            $riskScore = 0;
            // Sirf Browser/Platform Family compare hogi
            if ($device->platform !== $platform) $riskScore += 40;
            if ($device->browser !== $browser) $riskScore += 20;

            $device->fingerprint_hash = $fingerprintHash;
            $device->device_name = $deviceName; // Fix: Update device name
            $device->browser = $browser;
            $device->platform = $platform;
            $device->device_type = $deviceType;

            if ($riskScore >= 40 && !in_array($device->trust_level, ['BLOCKED', 'SUSPICIOUS'])) {
                $device->trust_level = 'SUSPICIOUS';
            }
        }
        $device->user_agent = $userAgent;
    }

    private function buildTempDevice($deviceId, $userAgent, $appVersion, $ip, $isFallback, $language, Agent $agent)
    {
        $browser = $agent->browser() ?: 'Unknown';
        $platform = $agent->platform() ?: 'Unknown';

        $isBot = $this->isBot($userAgent);
        $isEmulator = $this->isEmulator($userAgent) || $agent->isRobot();

        $deviceType = 'UNKNOWN';
        if ($isBot) $deviceType = 'BOT';
        elseif ($isEmulator) $deviceType = 'EMULATOR';
        elseif ($agent->isMobile()) $deviceType = 'MOBILE';
        elseif ($agent->isTablet()) $deviceType = 'TABLET';
        elseif ($agent->isDesktop()) $deviceType = 'DESKTOP';

        $fingerprintHash = hash('sha256', $deviceId . '|' . $browser . '|' . $platform);

        $device = new UserDevice();
        $device->device_id = $deviceId;
        $device->user_agent = $userAgent;
        $device->last_ip_address = $ip;
        $device->app_version = $appVersion;
        $device->language = $language;
        $device->platform = $platform;
        $device->browser = $browser;
        $device->device_name = $agent->device() ?: 'Unknown Device';
        $device->device_type = $deviceType;
        $device->is_emulator = ($isBot || $isEmulator);
        $device->fingerprint_hash = $fingerprintHash;
        
        $device->trusted_at = null; // Explicitly null
        $device->trust_level = ($isFallback || $isBot || $isEmulator) ? 'SUSPICIOUS' : 'NEW';

        return $device;
    }

    private function isBot(string $userAgent): bool
    {
        $botPatterns = ['PostmanRuntime', 'curl', 'python-requests', 'GuzzleHttp', 'HeadlessChrome', 'Puppeteer', 'PhantomJS'];
        foreach ($botPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $userAgent)) return true;
        }
        return false;
    }

    private function isEmulator(string $userAgent): bool
    {
        $emulatorPatterns = ['Android.*Build', 'Genymotion', 'Nox', 'BlueStacks', 'LDPlayer'];
        foreach ($emulatorPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $userAgent)) return true;
        }
        return false;
    }
}