<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Services\DeviceIdentificationService;
use App\Models\UserDevice;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Cache;

class DeviceIdentificationMiddleware
{
    protected $deviceService;

    public function __construct(DeviceIdentificationService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function handle(Request $request, Closure $next)
    {
        $rawDeviceId = $request->header('X-Device-ID');
        $userAgent   = $request->userAgent();
        $language    = $request->header('Accept-Language');
        $ip          = $request->ip();

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        if (empty($rawDeviceId)) {
            $platform = $agent->platform() ?: 'Unknown';
            $browser  = $agent->browser() ?: 'Unknown';
            $deviceId = 'fb_' . hash('sha256', $userAgent . $language . $ip);
        } else {
            $deviceId = $rawDeviceId;
        }

        $isDeviceBlocked = Cache::remember("blocked_device:{$deviceId}", 300, function () use ($deviceId) {
            return UserDevice::where('device_id', $deviceId)->where('trust_level', 'BLOCKED')->exists();
        });

        if ($isDeviceBlocked) {
            if (auth('api')->check()) {
                auth('api')->logout();
            }
            return response()->json([
                'status'       => 403,
                'message'      => 'This device is permanently blocked due to security violations.',
                'force_logout' => true
            ], 403);
        }

        if (!auth('api')->check()) {
            return $next($request);
        }

        $user       = auth('api')->user();
        $appVersion = $request->header('X-App-Version', '1.0.0');

        $device = $this->deviceService->processDevice($user, $deviceId, $appVersion, $ip, $userAgent, $language, $agent);

        $request->attributes->set('current_device', $device);

        return $next($request);
    }
}