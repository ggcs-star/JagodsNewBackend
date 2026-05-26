<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\v1\PrivateUserResource;
use App\Http\Resources\v1\RestaurantResource;
use App\Http\Services\DeviceIdentificationService; 

class LoginController extends Controller
{
    protected $deviceService;

    public function __construct(DeviceIdentificationService $deviceService)
    {
        $this->middleware('auth:api', ['except' => ['action']]);
        $this->deviceService = $deviceService; 
    }

    public function action(LoginRequest $request)
    {
        $login = trim($request->email);

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $credentials = [
                'email' => $login,
                'password' => $request->password
            ];
        } else {
            $login = preg_replace('/[^0-9]/', '', $login);
            $credentials = [
                'phone' => $login,
                'password' => $request->password
            ];
        }

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'data'    => [],
                'message' => 'You try to using invalid username or password',
                'status'  => 401,
            ], 401);
        }

        $user = auth('api')->user();
        $role = $request->role;

        if ($user->status == UserStatus::INACTIVE) {
            auth('api')->logout();
            return response()->json([
                'data'    => [],
                'message' => 'Your account currently inactive. you can\'t login our system.',
                'status'  => 401,
            ], 401);
        }

        if ($role && ($role != $user->myrole)) {
            auth('api')->logout(); 
            return response()->json([
                'data'    => [],
                'message' => "You don't have permission to login",
                'status'  => 401,
            ], 401);
        }

        if ($role == UserRole::WAITER) {
            $restaurant = !blank($user->waiter->restaurant) ? new RestaurantResource($user->waiter->restaurant) : [];
            $waiter = $user->waiter->id;
        } else {
            $restaurant = !blank($user->restaurant) ? new RestaurantResource($user->restaurant) : [];
            $waiter = 0;
        }

        $device = $this->deviceService->processDevice(
            $user,
            $request->header('X-Device-ID'),
            $request->header('X-App-Version', '1.0.0'),
            $request->ip(),
            $request->userAgent(),
            $request->header('Accept-Language')
        );

        $device->increment('login_count'); 
        $device->update([
            'last_login_at' => now(),
            'is_current' => true 
        ]);

        /*
        if ($device->trust_level === 'SUSPICIOUS') {
             // trigger OTP flow...
        }
        */

        return (new PrivateUserResource($user))
            ->additional([
                'token' => $token,
                'restaurant'  => $restaurant,
                'waiter_id'  => $waiter,
            ]);
    }
}