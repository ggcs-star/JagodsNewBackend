<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Services\DeviceIdentificationService;

class DeviceIdentificationMiddleware
{
    protected $deviceService;

    public function __construct(DeviceIdentificationService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();
        $rawDeviceId = $request->header('X-Device-ID');
        $appVersion = $request->header('X-App-Version', '1.0.0');
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $language = $request->header('Accept-Language');
        $device = $this->deviceService->processDevice($user, $rawDeviceId, $appVersion, $ip, $userAgent, $language);


        if ($device->trust_level === 'BLOCKED') {
            return response()->json([
                'error' => 'Device blocked due to policy violations.'
            ], 403);
        }

        $request->attributes->set('current_device', $device);

        return $next($request);
    }
}