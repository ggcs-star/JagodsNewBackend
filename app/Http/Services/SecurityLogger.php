<?php

namespace App\Http\Services;

use App\Models\SecurityAuditLog;
use Illuminate\Http\Request;

class SecurityLogger
{
    /**
     * @param Request $request
     * @param string $eventCategory (e.g., AUTH, CART, PAYMENT, SECURITY)
     * @param string $actionType (e.g., otp_failed, added_to_cart)
     * @param int $severityScore (1 to 10)
     * @param int|null $responseStatus (e.g., 200, 403)
     * @param array $metadata (Extra info)
     */
    public static function log(Request $request, $eventCategory, $actionType, $severityScore = 1, $responseStatus = 200, $metadata = [])
    {
        $user = auth()->user();
        $device = $request->attributes->get('current_device');

        SecurityAuditLog::create([
            'user_id' => $user ? $user->id : null,
            'user_device_id' => $device ? $device->id : null,
            'device_id' => $device ? $device->device_id : $request->header('X-Device-ID'),
            'event_category' => $eventCategory,
            'action_type' => $actionType,
            'api_endpoint' => $request->path(),
            'ip_address' => $request->ip(),
            'severity_score' => $severityScore,
            'response_status' => $responseStatus,
            'metadata' => $metadata,
        ]);
    }
}