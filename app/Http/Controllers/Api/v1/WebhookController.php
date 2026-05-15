<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Enums\PaymentStatus;
use App\Http\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        // Yahan hum TransactionService ko inject kar rahe hain
        $this->transactionService = $transactionService;
    }

    public function razorpay(Request $request)
    {
        // 1. Webhook Secret aur Signature nikalna
        $webhookSecret = setting('razorpay_webhook_secret');
        $signature = $request->header('X-Razorpay-Signature');
        $payload = $request->getContent(); // Raw JSON payload chahiye signature verify karne ke liye

        // 2. Security Check: Signature Verify karna
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        // hash_equals use karna zaroori hai taaki Timing Attacks se bacha ja sake
        if (!hash_equals($expectedSignature, $signature)) {
            Log::error('Razorpay Webhook: Invalid Signature Detected.', ['ip' => $request->ip()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // 3. Payload ko Array mein convert karna
        $data = json_decode($payload, true);

        // 4. "payment.captured" event handle karna
        // (Razorpay tabhi 'payment.captured' bhejta hai jab paise successfully account mein aa jate hain)
        if (isset($data['event']) && $data['event'] === 'payment.captured') {

            $paymentEntity = $data['payload']['payment']['entity'];

            $razorpayOrderId = $paymentEntity['order_id'];
            $razorpayPaymentId = $paymentEntity['id'];

            // 5. Database mein wo order dhundho jiska razorpay_order_id match karta ho
            $order = Order::where('payment_order_id', $razorpayOrderId)->first();

            if ($order) {
                // 6. Agar order pehle se PAID nahi hai, toh use PAID mark karo
                if ($order->payment_status !== PaymentStatus::PAID) {

                    DB::transaction(function () use ($order, $razorpayPaymentId) {
                        $order->update([
                            'payment_status' => PaymentStatus::PAID,
                            'payment_id' => $razorpayPaymentId,
                        ]);

                        // Ledger/Transaction Entry create karo
                        $this->transactionService->payment($order);
                    });

                    Log::info("Razorpay Webhook: Order ID {$order->id} successfully marked as PAID.");
                } else {
                    Log::info("Razorpay Webhook: Order ID {$order->id} was already PAID (Ignored).");
                }
            } else {
                Log::warning("Razorpay Webhook: Order not found for Razorpay Order ID {$razorpayOrderId}");
            }
        }

        // 7. VERY IMPORTANT: Hamesha Razorpay ko 200 OK return karo, 
        // warna Razorpay baar-baar same request bhejta rahega
        return response()->json(['status' => 'success'], 200);
    }
}