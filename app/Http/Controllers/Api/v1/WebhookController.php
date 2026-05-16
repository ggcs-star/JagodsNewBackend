<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\PaymentStatus;
use App\Enums\OrderStatus;
use App\Http\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendPetpoojaOrderJob;
use App\Jobs\SendOrderNotificationsJob;
use Exception;

class WebhookController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function razorpay(Request $request)
    {
        try {
            $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET') ?: setting('razorpay_webhook_secret');
            $signature = $request->header('X-Razorpay-Signature');
            $payload = $request->getContent();

            if (!$signature) {
                return response()->json(['error' => 'Missing signature'], 400);
            }

            $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::error('Razorpay Webhook: Invalid Signature Detected.', ['ip' => $request->ip()]);
                return response()->json(['error' => 'Invalid signature'], 400);
            }

            $data = json_decode($payload, true);

            if (isset($data['event']) && $data['event'] === 'payment.captured') {

                $paymentEntity = $data['payload']['payment']['entity'];

                $razorpayOrderId = $paymentEntity['order_id'];
                $razorpayPaymentId = $paymentEntity['id'];
                $paymentAmount = $paymentEntity['amount'];
                $paymentCurrency = $paymentEntity['currency'];

                $order = Order::where('payment_order_id', $razorpayOrderId)->first();

                if ($order) {

                    $expectedAmountInPaise = (int) round($order->total * 100);

                    if ($paymentAmount !== $expectedAmountInPaise) {
                        Log::error("Razorpay Webhook: Amount mismatch for Order {$order->id}");
                        return response()->json(['error' => 'Amount mismatch'], 400);
                    }

                    if ($paymentCurrency !== 'INR') {
                        Log::error("Razorpay Webhook: Currency mismatch for Order {$order->id}");
                        return response()->json(['error' => 'Currency mismatch'], 400);
                    }

                    if ($order->payment_status !== PaymentStatus::PAID) {

                        DB::transaction(function () use ($order, $paymentEntity, $razorpayPaymentId) {

                            $order->update([
                                'status' => OrderStatus::PENDING,
                                'payment_status' => PaymentStatus::PAID,
                                'payment_id' => $razorpayPaymentId,
                                'payment_response' => json_encode($paymentEntity),
                            ]);

                            $adminBalanceId = 1;
                            $userBalanceId = $order->user->balance_id;

                            $this->transactionService->addFund(
                                0,
                                $userBalanceId,
                                $order->payment_method,
                                $order->total,
                                $order->id
                            );

                            if ($adminBalanceId != $userBalanceId) {
                                $this->transactionService->payment(
                                    $userBalanceId,
                                    $adminBalanceId,
                                    $order->total,
                                    $order->id
                                );
                            }
                        });

                        // C. PUSH TO POS & NOTIFY: Payment confirm ho gayi, ab Jobs trigger karo!
                        // SendPetpoojaOrderJob::dispatch($order->id)->afterCommit();
                        // SendOrderNotificationsJob::dispatch($order->id)->afterCommit();

                        Log::info("Razorpay Webhook: Order ID {$order->id} successfully marked as PAID and Jobs Dispatched.");
                    } else {
                        Log::info("Razorpay Webhook: Order ID {$order->id} was already PAID (Ignored).");
                    }
                } else {
                    Log::warning("Razorpay Webhook: Order not found for Razorpay Order ID {$razorpayOrderId}");
                }
            }

            return response()->json(['status' => 'success'], 200);

        } catch (Exception $e) {
            Log::error('Razorpay Webhook Error: ' . $e->getMessage());

            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}