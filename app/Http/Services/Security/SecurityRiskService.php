<?php

namespace App\Http\Services\Security;

use App\Models\UserDevice;
use App\Models\User;
use Carbon\Carbon;

class SecurityRiskService
{
    public function analyzeRisk(UserDevice $device, User $user, string $currentIp, $currentLat = null, $currentLon = null): array
    {
        $riskScore = 0;
        $riskReasons = [];

        if ($device->failed_attempts >= 3) {
            $riskScore += 40;
            $riskReasons[] = "Multiple failed OTP/Login attempts ({$device->failed_attempts}).";
        }

        if ($device->vpn_detected) {
            $riskScore += 30;
            $riskReasons[] = 'VPN usage detected.';
        }
        if ($device->proxy_detected) {
            $riskScore += 20;
            $riskReasons[] = 'Proxy connection detected.';
        }

        if ($device->device_type === 'BOT') {
            $riskScore += 0; 
            $riskReasons[] = 'Malicious Bot or Automated Script detected.';
        } elseif ($device->device_type === 'EMULATOR' || $device->is_emulator) {
            $riskScore += 40; 
            $riskReasons[] = 'Emulator behavior detected.';
        }

        if ($device->trust_level === 'NEW') {
            $riskScore += 15; 
        } elseif ($device->trust_level === 'SUSPICIOUS') {
            $riskScore += 50; 
        } elseif ($device->trust_level === 'TRUSTED') {
            $riskScore -= 10; 
        }

        if ($currentLat && $currentLon && $device->lat && $device->lon && $device->last_login_at) {
            $isImpossible = $this->checkImpossibleTravel(
                $device->lat,
                $device->lon,
                $currentLat,
                $currentLon,
                $device->last_login_at
            );

            if ($isImpossible) {
                $riskScore += 60; 
                $riskReasons[] = 'Impossible travel detected (Geo-Velocity mismatch).';
            }
        }

        $action = $this->determineAction($riskScore);

        if ($device->exists) {
            $device->update(['risk_score' => $riskScore]);
        }

        return [
            'risk_score' => $riskScore,
            'action' => $action,
            'reasons' => $riskReasons
        ];
    }

    private function checkImpossibleTravel($oldLat, $oldLon, $newLat, $newLon, $lastLoginTime): bool
    {
        $distance = $this->calculateHaversineDistance($oldLat, $oldLon, $newLat, $newLon);
        $hoursPassed = Carbon::parse($lastLoginTime)->diffInHours(now());

        if ($distance < 1)
            return false;

        $hoursPassed = $hoursPassed > 0 ? $hoursPassed : (Carbon::parse($lastLoginTime)->diffInMinutes(now()) / 60);
        if ($hoursPassed <= 0)
            return true;

        $speed = $distance / $hoursPassed;

        if ($speed > 1000)
            return true;

        return false;
    }

    private function calculateHaversineDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    private function determineAction(int $score): string
    {
        if ($score >= 80)
            return 'BLOCK';
        if ($score >= 50)
            return 'REQUIRE_OTP';
        if ($score >= 30)
            return 'RESTRICT';
        return 'ALLOW';
    }
}