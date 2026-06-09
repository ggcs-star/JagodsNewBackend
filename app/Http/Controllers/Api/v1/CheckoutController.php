<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\CheckoutRequest;
use App\Http\Requests\Api\VerifyPaymentRequest;
use App\Http\Services\CheckoutServiceNew;
use App\Http\Services\PaymentServiceNew;
use App\Http\Services\CartService;
use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderInvoiceJob;

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
            $currentDevice = $request->attributes->get('current_device');
            $order = $this->checkoutService->checkout($cart, (int) $request->payment_method, $currentDevice);

            if ((int) $request->payment_method === PaymentMethod::CASH_ON_DELIVERY) {

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


            if ($order && (int) $request->payment_method !== PaymentMethod::CASH_ON_DELIVERY) {

                $order->orderLines()->delete();
                $order->delete();

                Log::error("Payment Error: Order ID {$order->id} deleted due to API failure. Exception: " . $e->getMessage());
            }

            $statusCode = (int) $e->getCode();
            $statusCode = ($statusCode >= 100 && $statusCode <= 599) ? $statusCode : 400;

            return $this->errorResponse($e->getMessage() . " (Please try again)", $statusCode);
        }
    }

    public function verifyPayment(VerifyPaymentRequest $request)
    {
        try {

            $order = Order::find($request->order_id);

            if (!$order) {
                return $this->errorResponse('Order not found', 404);
            }

            if ($order->user_id !== auth()->id()) {
                return $this->errorResponse(
                    'You are not authorized to verify this order',
                    403
                );
            }

            $this->paymentService->verify($order, $request->validated());

            $order->update([
                'status' => \App\Enums\OrderStatus::PENDING
            ]);

            SendOrderInvoiceJob::dispatch($order);

            return $this->successResponse([
                'status' => 200,
                'message' => 'Payment verified successfully',
            ]);

        } catch (Exception $e) {

            $statusCode = (int) $e->getCode();
            $statusCode = ($statusCode >= 100 && $statusCode <= 599)
                ? $statusCode
                : 400;

            return $this->errorResponse(
                $e->getMessage(),
                $statusCode
            );
        }
    }

    public function repayOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required|numeric|exists:orders,id'
        ], [
            'order_id.required' => 'Order ID is required.',
            'order_id.exists' => 'Order not found. Please provide a valid order ID.'
        ]);

        try {
            $order = Order::where('id', $request->order_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$order) {
                return $this->errorResponse('This order does not belong to you or has been removed.', 404);
            }

            if ($order->payment_status == \App\Enums\PaymentStatus::PAID) {
                return $this->errorResponse('This order is already paid.', 400);
            }

            if ($order->status == \App\Enums\OrderStatus::CANCEL) {
                return $this->errorResponse('Cancelled orders cannot be paid.', 400);
            }

            $paymentData = $this->paymentService->create($order);

            return $this->successResponse([
                'status' => 200,
                'message' => 'Repayment initiated successfully',
                'data' => $paymentData
            ]);

        } catch (Exception $e) {
            $statusCode = (int) $e->getCode();
            $statusCode = ($statusCode >= 100 && $statusCode <= 599) ? $statusCode : 400;
            return $this->errorResponse($e->getMessage(), $statusCode);
        }
    }
}