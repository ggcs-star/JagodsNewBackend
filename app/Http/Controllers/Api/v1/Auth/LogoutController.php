<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeviceSession;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api'); 
    }

    public function action(Request $request)
    {
        $request->validate([
            'refresh_token' => 'required|string'
        ]);

        $hashedToken = hash('sha256', $request->refresh_token);

        DeviceSession::where('refresh_token', $hashedToken)
            ->whereNull('revoked_at')
            ->update([
                'revoked_at' => now()
            ]);

        auth('api')->logout();

        return response()->json([
            'status' => 200,
            'data' => [],
            'message' => 'Successfully logged out. Session securely revoked.'
        ], 200);
    }
}