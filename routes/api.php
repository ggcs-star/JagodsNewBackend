<?php

use App\Http\Controllers\Api\v1\AddressController;
use App\Http\Controllers\Api\v1\AdminCommissionReportController;
use App\Http\Controllers\Api\v1\AdministratorController;
use App\Http\Controllers\Api\v1\Auth\LoginController;
use App\Http\Controllers\Api\v1\Auth\LogoutController;
use App\Http\Controllers\Api\v1\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\v1\Auth\MeController;
use App\Http\Controllers\Api\v1\Auth\RegisterController;
use App\Http\Controllers\Api\v1\Auth\SocialLoginController;
use App\Http\Controllers\Api\v1\BannerController;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\CategoryController;
use App\Http\Controllers\Api\v1\CouponController;
use App\Http\Controllers\Api\v1\CuisineController;
use App\Http\Controllers\Api\v1\MenuItemController;
use App\Http\Controllers\Api\v1\NotificationOrderController;
use App\Http\Controllers\Api\v1\OrderController;
use App\Http\Controllers\Api\v1\OtpLoginController;
use App\Http\Controllers\Api\v1\PopularRestaurantController;
use App\Http\Controllers\Api\v1\PushNotificationController;
use App\Http\Controllers\Api\v1\RequestWithdrawController;
use App\Http\Controllers\Api\v1\ReservationController;
use App\Http\Controllers\Api\v1\RestaurantController;
use App\Http\Controllers\Api\v1\RestaurantOrderController;
use App\Http\Controllers\Api\v1\RestaurantOwnerSalesReportController;
use App\Http\Controllers\Api\v1\RestaurantReservationController;
use App\Http\Controllers\Api\v1\SearchController;
use App\Http\Controllers\Api\v1\SettingController;
use App\Http\Controllers\Api\v1\StatusController;
use App\Http\Controllers\Api\v1\TableController;
use App\Http\Controllers\Api\v1\TimeSlotController;
use App\Http\Controllers\Api\v1\TransactionController;
use App\Http\Controllers\Api\v1\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CheckoutController;
use App\Http\Controllers\Api\v1\WebhookController;
use App\Http\Controllers\Api\v1\DeviceVerificationController;
use App\Http\Controllers\Api\v1\Auth\RefreshTokenController;
use App\Http\Controllers\Api\v1\UniversalOtpController;
use App\Http\Controllers\Api\v1\Auth\ActiveSessionController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {

    Route::post('login', [LoginController::class, 'action'])->middleware('throttle:login_attempts');
    Route::post('refresh-token', [RefreshTokenController::class, 'refresh']);
    // Route::post('reg', [RegisterController::class, 'action']);
    Route::post('reg', [RegisterController::class, 'sendRegisterOtp']);
    Route::post('register/verify-otp', [RegisterController::class, 'verifyRegisterOtp']);
    Route::post('register/resend-otp', [RegisterController::class, 'resendOtp']);
    Route::post('social-login', [SocialLoginController::class, 'action'])->middleware('throttle:login_attempts');
    Route::post('logout', [LogoutController::class, 'action']);
   
    Route::get('sessions', [ActiveSessionController::class, 'index']);

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->middleware('throttle:otp_send');

    Route::post('/otp/send', [UniversalOtpController::class, 'send'])->middleware('throttle:otp_send');
    Route::post('/otp/verify', [UniversalOtpController::class, 'verify'])->middleware('throttle:login_attempts');

    // Route::post('otp-login', [OtpLoginController::class, 'getOtp'])->middleware('throttle:otp_send');
    // Route::post('verify-otp', [OtpLoginController::class, 'verifyOtp'])->middleware('throttle:login_attempts');

    // Route::post('/device/send-otp', [DeviceVerificationController::class, 'sendOtp'])->middleware('throttle:otp_send');
    // Route::post('/device/verify-otp', [DeviceVerificationController::class, 'verifyOtp'])->middleware('throttle:login_attempts');

    Route::get('me', [MeController::class, 'action']);
    Route::get('refresh', [MeController::class, 'refresh']);
    Route::post('profile', [MeController::class, 'update']);
    Route::put('change-password', [MeController::class, 'changePassword']);
    Route::put('device', [MeController::class, 'device']);

    Route::get('review/{id}', [MeController::class, 'review']);
    Route::post('review', [MeController::class, 'saveReview'])->middleware('throttle:cart_actions');
    Route::get('report/{id}', [MeController::class, 'reportCheck']);
    Route::post('report', [MeController::class, 'storeReport'])->middleware('throttle:cart_actions');

    Route::post('fcm-subscribe', [PushNotificationController::class, 'fcmSubscribe']);
    Route::post('fcm-unsubscribe', [PushNotificationController::class, 'fcmUnsubscribe']);

    Route::get('cart', [CartController::class, 'index'])->middleware('throttle:cart_fetch');
    Route::post('cart', [CartController::class, 'store'])->middleware('throttle:cart_actions');
    Route::get('cart/removeItem/{id}', [CartController::class, 'remove'])->middleware('throttle:cart_actions');
    Route::post('cart/clear', [CartController::class, 'clear'])->middleware('throttle:cart_actions');
    Route::post('cart-quantity', [CartController::class, 'quantity'])->middleware('throttle:cart_actions');
    Route::post('cart/update', [CartController::class, 'update'])->middleware('throttle:cart_actions');

    Route::post('coupon', [CouponController::class, 'apply'])->middleware('throttle:cart_actions');
    Route::post('cart/apply-coupon', [CartController::class, 'applyCoupon'])->middleware('throttle:cart_actions');
    Route::post('generate-coupons', [CouponController::class, 'generateCoupons']);

    Route::middleware(['require.trusted.device', 'throttle:checkout_strict'])->group(function () {
        Route::post('/checkout', [CheckoutController::class, 'checkout']);
    });

    Route::post('/repay-order', [CheckoutController::class, 'repayOrder'])->middleware('throttle:checkout_strict');
    Route::post('payment/verify', [CheckoutController::class, 'verifyPayment'])->middleware('throttle:payment_verify');

    Route::post('webhooks/razorpay', [WebhookController::class, 'razorpay']);

    Route::get('reservation', [ReservationController::class, 'index']);
    Route::post('restaurant/reservation/booking', [ReservationController::class, 'store'])->middleware('throttle:checkout_strict');
    Route::post('reservation/check', [ReservationController::class, 'check']);
    Route::put('reservation/status/{id}', [ReservationController::class, 'update']);

    Route::get('orders', [OrderController::class, 'index']);
    Route::post('orders', [OrderController::class, 'store'])->middleware('throttle:checkout_strict');
    Route::put('orders/{id}', [OrderController::class, 'update']);
    Route::get('orders/{id}/show', [OrderController::class, 'show']);
    Route::post('orders/payment', [OrderController::class, 'orderPayment'])->middleware('throttle:payment_verify');
    Route::get('orders/{id}/download-attachment', [OrderController::class, 'attachment']);
    Route::get('orders/cancel/{id}', [OrderController::class, 'orderCancel']);

    Route::get('status/{name}/{flip?}', [StatusController::class, 'index']);
    Route::get('status-order/{id}', [StatusController::class, 'getOrderStatus']);
    Route::get('settings', [SettingController::class, 'index']);
    Route::get('banners', [BannerController::class, 'index']);
    Route::post('sort-banner', [BannerController::class, 'sortBanner'])->name('sort.banner');

    Route::get('category', [CategoryController::class, 'index']);
    Route::get('category/{id}', [CategoryController::class, 'index']);
    Route::get('category/{id}/show', [CategoryController::class, 'show']);

    Route::get('cuisine', [CuisineController::class, 'index']);
    Route::get('cuisine/{id}', [CuisineController::class, 'index']);
    Route::get('cuisine/{id}/show', [CuisineController::class, 'show']);

    Route::get('popular-restaurant', [PopularRestaurantController::class, 'index']);
    Route::get('/restaurant/index/{id?}/{status?}/{applied?}', [RestaurantController::class, 'index']);
    Route::get('restaurant/{id}', [RestaurantController::class, 'show']); 
    // Route::get('/search', [SearchController::class, 'index']);
    Route::get('/search', [SearchController::class, 'globalSearch']);
    Route::get('restaurant-menuItem/menuItem', [MenuItemController::class, 'index']);
    Route::get('restaurant-menuItem/menuItem/{id}', [MenuItemController::class, 'index']);
    Route::get('restaurant-menuItem/menuItem/{id}/show', [MenuItemController::class, 'show']);

    Route::get('restaurant-table/table', [TableController::class, 'index']);
    Route::get('restaurant-table/table/{id}', [TableController::class, 'show']);
    Route::post('restaurant-table/table', [TableController::class, 'store']);
    Route::put('restaurant-table/table/{id}', [TableController::class, 'update']);
    Route::delete('restaurant-table/table/{id}', [TableController::class, 'delete']);

    Route::get('restaurant-timeSlot/timeSlot', [TimeSlotController::class, 'index']);
    Route::get('withdraw', [WithdrawController::class, 'index']);
    Route::get('request-withdraw', [RequestWithdrawController::class, 'index']);
    Route::post('request-withdraw', [RequestWithdrawController::class, 'store']);
    Route::put('request-withdraw/{id}', [RequestWithdrawController::class, 'update']);
    Route::delete('request-withdraw/{id}', [RequestWithdrawController::class, 'delete']);

    Route::get('restaurant-order', [RestaurantOrderController::class, 'index']);
    Route::get('restaurant-order/history', [RestaurantOrderController::class, 'history']);
    Route::get('restaurant-order/{id}', [RestaurantOrderController::class, 'show']);
    Route::put('restaurant-order/{id}', [RestaurantOrderController::class, 'update']);
    Route::get('restaurant-reservation', [RestaurantReservationController::class, 'index']);

    Route::get('notification-order', [NotificationOrderController::class, 'index']);
    Route::put('notification-order/{id}/update', [NotificationOrderController::class, 'orderAccept']);
    Route::put('notification-order-product-receive/{id}/update', [NotificationOrderController::class, 'OrderProductReceive']);
    Route::put('notification-order-status/{id}/update', [NotificationOrderController::class, 'orderStatus']);
    Route::get('notification-order/{id}/show', [NotificationOrderController::class, 'show']);
    Route::get('notification-order/history', [NotificationOrderController::class, 'history']);

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('restaurant-owner-sales-report', [RestaurantOwnerSalesReportController::class, 'index']);
    Route::post('restaurant-owner-sales-report', [RestaurantOwnerSalesReportController::class, 'index']);
    Route::get('admin-commission-report', [AdminCommissionReportController::class, 'index']);
    Route::post('admin-commission-report', [AdminCommissionReportController::class, 'index']);

    Route::resource('administrators', AdministratorController::class);
    Route::get('get-administrators', [AdministratorController::class, 'getAdministrators'])->name('administrators.get-administrators');

    Route::get('address', [AddressController::class, 'index']);
    Route::post('address-store', [AddressController::class, 'store']);
    Route::put('address-update/update/{id}', [AddressController::class, 'update']);
    Route::delete('address-delete/{id}', [AddressController::class, 'destroy']);
});

Route::get('/geo-test', function () {

    $location = geoip('49.36.15.20');

    return response()->json([
        'ip' => $location->ip,
        'country' => $location->country,
        'city' => $location->city,
        'state' => $location->state_name,
        'timezone' => $location->timezone,
        'lat' => $location->lat,
        'lon' => $location->lon,
    ]);
});