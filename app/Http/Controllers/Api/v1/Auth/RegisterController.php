<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\v1\RegisterResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use App\Http\Services\Auth\AuthRegisterService;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(AuthRegisterService $registerService)
    {
        $this->registerService = $registerService;
    }


    public function sendRegisterOtp(Request $request)
    {
        $validator = new RegisterRequest();
        $rules = $validator->rules();

        if ($request->get('role') == 3 || $request->get('role') == 4) {
            $rules['deposit_amount'] = 'nullable|numeric';
            $rules['limit_amount'] = 'nullable|numeric';
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ], 422);
        }

        $role = Role::find($request->get('role'));
        if (blank($role)) {
            return response()->json([
                'status' => 401,
                'message' => 'Given role not found',
                'data' => [],
            ], 401);
        }

        $response = $this->registerService->processRegistrationOtp(
            $request->all(),
            $request->header('X-Device-ID'),
            $request->ip()
        );

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json([
            'status' => 200,
            'message' => $response['message'],
            'temp_token' => $response['temp_token'],
            'expires_in' => $response['expires_in']
        ], 200);
    }


    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'temp_token' => 'required|string',
            'otp' => 'required|numeric'
        ]);

        $response = $this->registerService->verifyAndRegister(
            $request,
            $request->temp_token,
            $request->otp,
            $request->header('X-Device-ID')
        );

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message']
            ], $response['code']);
        }

        $loginData = $response['login_data'];

        return (new RegisterResource($response['user']))
            ->additional([
                'token' => $loginData['token'],
                'refresh_token' => $loginData['refresh_token'],
                'expires_in' => $loginData['expires_in'],
                'restaurant' => $loginData['restaurant_data'] ?? [],
                'waiter_id' => $loginData['waiter_id_data'] ?? 0,
            ], 200);
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'temp_token' => 'required|string',
        ]);

        $deviceId = $request->header('X-Device-ID');
        if (empty($deviceId)) {
            $deviceId = 'fb_' . hash('sha256', $request->userAgent() . $request->header('Accept-Language') . $request->ip());
        }

        $response = $this->registerService->resendRegistrationOtp(
            $request->temp_token,
            $deviceId,
            $request->ip()
        );

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message']
            ], $response['code']);
        }

        return response()->json([
            'status' => 200,
            'message' => $response['message'],
            'expires_in' => $response['expires_in']
        ], 200);
    }
}