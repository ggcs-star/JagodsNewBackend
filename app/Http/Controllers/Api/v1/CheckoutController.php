<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\BackendController;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Requests\Api\VerifyPaymentRequest;
use App\Http\Services\CheckoutServiceNew;
use App\Http\Services\PaymentServiceNew;
use App\Http\Services\CartService;
use App\Models\Cart;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class CheckoutController extends BackendController
{
    use ApiResponse;

    protected $checkoutService;
    protected $paymentService;
    protected $cartService;

    public function __construct(
        CheckoutServiceNew $checkoutService,
        PaymentServiceNew $paymentService,
        CartService $cartService
    ) {
        parent::__construct();
        $this->middleware('auth:api');

        $this->checkoutService = $checkoutService;
        $this->paymentService = $paymentService;
        $this->cartService = $cartService;
    }

    public function checkout(CheckoutRequest $request)
    {
        $order = null;

        try {
            $cart = Cart::with(['items.menuItem', 'items.variation', 'coupon', 'address'])
                ->where('user_id', auth()->id())
                ->first();

            if (!$cart) {
                return $this->errorResponse('Cart not found', 404);
            }


            $order = $this->checkoutService->checkout($cart, $request->payment_method);


            if ($request->payment_method === 'cod') {


                $this->cartService->clearCart(auth()->id());

                return $this->successResponse([
                    'status' => 200,
                    'message' => 'Order placed successfully',
                    'data' => $order,
                ]);
            }


            $payment = $this->paymentService->create($order);

            $this->cartService->clearCart(auth()->id());

            return $this->successResponse([
                'status' => 200,
                'message' => 'Payment initialized',
                'data' => [
                    'order' => $order,
                    'payment' => $payment,
                ]
            ]);

        } catch (Exception $e) {

            if ($order && $request->payment_method !== 'cod') {

                $order->orderLines()->delete();
                $order->delete();

                Log::error("Razorpay Error: Order ID {$order->id} deleted due to API failure. Exception: " . $e->getMessage());
            }

            $statusCode = (int) $e->getCode();
            $statusCode = ($statusCode >= 100 && $statusCode <= 599) ? $statusCode : 400;

            return $this->errorResponse($e->getMessage() . " (Please try again)", $statusCode);
        }
    }

    public function verifyPayment(VerifyPaymentRequest $request)
    {
        try {
            $order = Order::findOrFail($request->order_id);

            $this->paymentService->verify($order, $request->validated());


            $order->update([
                'status' => \App\Enums\OrderStatus::PENDING
            ]);

            return $this->successResponse([
                'status' => 200,
                'message' => 'Payment verified successfully',
            ]);

        } catch (Exception $e) {
            $statusCode = (int) $e->getCode();
            $statusCode = ($statusCode >= 100 && $statusCode <= 599) ? $statusCode : 400;
            return $this->errorResponse($e->getMessage(), $statusCode);
        }
    }
}