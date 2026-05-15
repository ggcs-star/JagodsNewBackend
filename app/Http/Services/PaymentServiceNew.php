<?php

namespace App\Http\Services;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\DB;
use Exception;

class PaymentServiceNew
{
    protected $razorpay;
    protected $transactionService;

    protected $razorpayKey;
    protected $razorpaySecret;

    public function __construct(TransactionService $transactionService)
    {
        $this->razorpayKey = env('RAZORPAY_KEY') ?: setting('razorpay_key');
        $this->razorpaySecret = env('RAZORPAY_SECRET') ?: setting('razorpay_secret');

        if (empty($this->razorpayKey) || empty($this->razorpaySecret)) {
            throw new Exception('Razorpay API keys missing.', 400);
        }

        $this->razorpay = new Api($this->razorpayKey, $this->razorpaySecret);

        $this->transactionService = $transactionService;
    }

    public function create(Order $order)
    {

        if ($order->payment_status == PaymentStatus::PAID) {
            throw new Exception('Order already paid');
        }

        $razorpayOrder = $this->razorpay->order->create([
            'receipt' => 'order_' . $order->id,
            'amount' => $order->total * 100,
            'currency' => 'INR',
        ]);

        $order->update([
            'payment_order_id' => $razorpayOrder['id'],
        ]);

        return [
            'order_id' => $order->id,
            'razorpay_order_id' => $razorpayOrder['id'],
            'amount' => $order->total * 100,
            'currency' => 'INR',
            'key' => $this->razorpayKey,
        ];
    }

    public function verify(Order $order, array $data)
    {
        $generatedSignature = hash_hmac(
            'sha256',
            $data['razorpay_order_id'] . "|" . $data['razorpay_payment_id'],
            $this->razorpaySecret
        );

        if ($generatedSignature !== $data['razorpay_signature']) {
            throw new Exception('Invalid payment signature', 400);
        }

        DB::transaction(function () use ($order, $data) {

            $order->update([
                'payment_status' => PaymentStatus::PAID,
                'payment_id' => $data['razorpay_payment_id'],
            ]);

            $this->transactionService->payment($order);
        });

        return true;
    }
}