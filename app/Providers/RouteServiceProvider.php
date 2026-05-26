<?php

namespace App\Providers;

use App\Models\Addon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
            $this->mapApiRoutes();
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            $this->mapWebRoutes();
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    /**
     * Helper function to get consistent Device ID for rate limiting
     */
    protected function resolveDeviceIdentifier(Request $request): string
    {
        $rawDeviceId = $request->header('X-Device-ID');

        if (!$rawDeviceId) {
            return 'fb_' . md5($request->userAgent() . $request->ip());
        }

        return $rawDeviceId;
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // 1. General API Limit
        RateLimiter::for('api', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinute(60)->by($request->user()?->id ?: $deviceId);
        });

        RateLimiter::for('login_attempts', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            $identifier = $request->input('email', $request->input('phone', '')) . '|' . $deviceId;

            return Limit::perMinute(5)->by($identifier)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'Too many login attempts. Please try again later.'
                    ], 429);
                });
        });

        RateLimiter::for('otp_send', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinutes(1, 2)->by($request->user()?->id ?: $deviceId)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'Too many OTP requests. Please wait a minute.'
                    ], 429);
                });
        });

        RateLimiter::for('cart_actions', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinute(20)->by($request->user()?->id ?: $deviceId)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'Too many cart requests. Please slow down.'
                    ], 429);
                });
        });

        RateLimiter::for('cart_fetch', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinute(30)->by($request->user()?->id ?: $deviceId)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'You are refreshing the cart too quickly. Please wait.'
                    ], 429);
                });
        });

        RateLimiter::for('checkout_strict', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinute(5)->by($request->user()?->id ?: $deviceId)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'Too many checkout attempts. Please try again after a minute.'
                    ], 429);
                });
        });

        RateLimiter::for('payment_verify', function (Request $request) {
            $deviceId = $this->resolveDeviceIdentifier($request);
            return Limit::perMinute(10)->by($request->user()?->id ?: $deviceId)
                ->response(function () {
                    return response()->json([
                        'status' => 429,
                        'message' => 'Suspicious payment activity detected. Please wait.'
                    ], 429);
                });
        });
    }

    protected function mapWebRoutes()
    {
        if (file_exists(storage_path('installed'))) {
            $addons = Addon::all();
            if (!blank($addons)) {
                foreach ($addons as $addon) {
                    if (isset(json_decode($addon->files)->web_route)) {
                        if (File::exists(__DIR__ . "/../../routes/{$addon->slug}.php")) {
                            Route::middleware('web')
                                ->group(__DIR__ . "/../../routes/{$addon->slug}.php");
                        }
                    }
                }
            }
        }
    }


    protected function mapApiRoutes()
    {
        if (file_exists(storage_path('installed'))) {
            $addons = Addon::all();
            if (!blank($addons)) {
                foreach ($addons as $addon) {
                    if (isset(json_decode($addon->files)->api_route)) {
                        if (File::exists(__DIR__ . "/../../routes/{$addon->slug}.php")) {
                            Route::prefix('api')
                                ->middleware('api')
                                ->group(__DIR__ . "/../../routes/{$addon->slug}-api.php");
                        }
                    }
                }
            }
        }
    }
}
