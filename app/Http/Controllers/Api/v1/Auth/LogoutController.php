<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Security\ActiveSessionService;

class LogoutController extends Controller
{
    protected $sessionService;

    public function __construct(ActiveSessionService $sessionService)
    {
        $this->middleware('auth:api');
        $this->sessionService = $sessionService;
    }

    public function action(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required_without_all:device_db_id,logout_all|string',
            'device_db_id' => 'required_without_all:refresh_token,logout_all|string',
            'logout_all' => 'required_without_all:refresh_token,device_db_id|boolean',
        ]);

        $userId = auth('api')->id();
        $currentDevice = $request->attributes->get('current_device');

        $response = $this->sessionService->handleLogoutProcess($request, $userId, $currentDevice);

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => $response['message']
        ], 200);
    }
}