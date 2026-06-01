<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\Security\ActiveSessionService;

class ActiveSessionController extends Controller
{
    protected $sessionService;

    public function __construct(ActiveSessionService $sessionService)
    {
        $this->middleware('auth:api');
        $this->sessionService = $sessionService;
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $currentDevice = $request->attributes->get('current_device');
        
        $sessions = $this->sessionService->getActiveSessions($userId, $currentDevice->id);

        return response()->json([
            'status' => 200,
            'message' => 'Active sessions retrieved successfully.',
            'data' => $sessions
        ], 200);
    }
}