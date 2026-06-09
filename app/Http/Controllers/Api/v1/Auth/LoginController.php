<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\v1\PrivateUserResource;
use App\Http\Services\Auth\AuthLoginService;

class LoginController extends Controller
{
    protected $authLoginService;

    public function __construct(AuthLoginService $authLoginService)
    {
        $this->middleware('auth:api', ['except' => ['action']]);
        $this->authLoginService = $authLoginService;
    }

    public function action(LoginRequest $request)
    {
        $login = trim($request->email);

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $login, 'password' => $request->password];
        } else {
            $login = preg_replace('/[^0-9]/', '', $login);
            $credentials = ['phone' => $login, 'password' => $request->password];
        }

        $response = $this->authLoginService->login($credentials, $request, $request->role);

        if (!$response['status']) {
            return response()->json([
                'status' => $response['code'],
                'message' => $response['message'],
                'requires_otp' => $response['requires_otp'] ?? false,
                'risk' => $response['risk'] ?? null,
            ], $response['code']);
        }

        return (new PrivateUserResource($response['user']))
            ->additional([
                'token' => $response['token'],
                'refresh_token' => $response['refresh_token'],
                'expires_in' => $response['expires_in'],
                'restaurant' => $response['restaurant_data'],
                'waiter_id' => $response['waiter_id_data'],
            ]);
    }
}