<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DeviceVerificationService;

class DeviceVerificationController extends Controller
{
    protected $verificationService;

    public function __construct(DeviceVerificationService $verificationService)
    {
        $this->middleware('auth:api');
        $this->verificationService = $verificationService;
    }

    public function sendOtp(Request $request)
    {
        $user = auth()->user();
        $device = $request->attributes->get('current_device');

        $result = $this->verificationService->sendOtp($user, $device);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], $result['code']);
        }

        return response()->json([
            'message' => $result['message'],
            'expires_in_minutes' => $result['expires_in_minutes'] ?? null
        ], $result['code']);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6'
        ]);

        $user = auth()->user();
        $device = $request->attributes->get('current_device');

        $result = $this->verificationService->verifyOtp($user, $device, $request->otp, $request);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], $result['code']);
        }

        return response()->json([
            'message' => $result['message']
        ], $result['code']);
    }
}