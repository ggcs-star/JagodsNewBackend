<?php

namespace App\Http\Services\Security;

use App\Models\UserDevice;
use App\Models\DeviceSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache; // 🚨 CACHE IMPORT ADDED
use Exception;

class ActiveSessionService
{
    public function getActiveSessions($userId, $currentDeviceDbId)
    {
        $devices = UserDevice::where('user_id', $userId)
            ->whereHas('sessions', function ($query) {
                $query->whereNull('revoked_at')
                    ->where('expires_at', '>', now());
            })
            ->orderBy('last_active_at', 'desc')
            ->get();

        return $devices->map(function ($device) use ($currentDeviceDbId) {
            $sessionName = "{$device->device_name} ({$device->browser} on {$device->platform})";

            return [
                'id' => $device->id,
                'session_name' => $sessionName,
                'device_id' => $device->device_id,
                'device_type' => $device->device_type,
                'last_ip_address' => $device->last_ip_address,

                'country' => $device->country ?? 'Unknown',
                'city' => $device->city ?? 'Unknown',
                'login_count' => $device->login_count,
                'trusted_at' => $device->trusted_at ? $device->trusted_at->format('M d, Y') : null,

                'last_active_at' => $device->last_active_at ? $device->last_active_at->diffForHumans() : 'Unknown',
                'is_current' => ($device->id === $currentDeviceDbId),
                'trust_level' => $device->trust_level
            ];
        });
    }

    public function handleLogoutProcess($request, $userId, $currentDevice)
    {
        try {
            return DB::transaction(function () use ($request, $userId, $currentDevice) {

                if ($request->has('device_db_id')) {
                    return $this->logoutSpecificDevice($userId, $request->device_db_id);
                }

                if ($request->has('logout_all') && $request->logout_all == true) {
                    return $this->logoutAllOtherDevices($userId, $currentDevice->id);
                }

                if ($request->has('refresh_token')) {
                    $hashedToken = hash('sha256', $request->refresh_token);

                    $session = DeviceSession::where('refresh_token', $hashedToken)
                        ->where('user_id', $userId)
                        ->whereNull('revoked_at')
                        ->first();

                    if ($session) {
                        $session->update(['revoked_at' => now()]);
                        Cache::forget("session_valid:{$session->id}");
                    }

                    auth('api')->logout();

                    return ['status' => true, 'code' => 200, 'message' => 'Successfully logged out from current device.'];
                }

                return ['status' => false, 'code' => 400, 'message' => 'Invalid logout request. Missing parameters.'];
            });

        } catch (Exception $e) {
            Log::error('Logout Flow Failed: ' . $e->getMessage());
            return ['status' => false, 'code' => 500, 'message' => 'Internal server error during logout.'];
        }
    }

    private function logoutSpecificDevice($userId, $deviceIdToLogout)
    {
        $device = UserDevice::where('user_id', $userId)->where('id', $deviceIdToLogout)->first();

        if (!$device) {
            return ['status' => false, 'code' => 404, 'message' => 'Device not found.'];
        }

        $activeSessions = DeviceSession::where('user_device_id', $device->id)
            ->whereNull('revoked_at')
            ->get(['id']);

        if ($activeSessions->isNotEmpty()) {
            DeviceSession::whereIn('id', $activeSessions->pluck('id'))->update(['revoked_at' => now()]);

            foreach ($activeSessions as $session) {
                Cache::forget("session_valid:{$session->id}");
            }
        }

        Log::info("Remote logout initiated", [
            'user_id' => $userId,
            'target_device_id' => $device->id,
            'action' => 'remote_logout',
            'severity' => 3
        ]);

        return ['status' => true, 'code' => 200, 'message' => 'Remote device logged out successfully.'];
    }

    private function logoutAllOtherDevices($userId, $currentDbId)
    {
        $activeSessions = DeviceSession::where('user_id', $userId)
            ->where('user_device_id', '!=', $currentDbId)
            ->whereNull('revoked_at')
            ->get(['id']);

        if ($activeSessions->isNotEmpty()) {
            DeviceSession::whereIn('id', $activeSessions->pluck('id'))->update(['revoked_at' => now()]);

            foreach ($activeSessions as $session) {
                Cache::forget("session_valid:{$session->id}");
            }
        }

        Log::warning("Panic logout triggered (All devices revoked)", [
            'user_id' => $userId,
            'safe_device_id' => $currentDbId,
            'action' => 'logout_all',
            'severity' => 4
        ]);

        return ['status' => true, 'code' => 200, 'message' => 'All other devices logged out securely.'];
    }
}