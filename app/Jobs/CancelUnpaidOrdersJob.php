<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\Discount;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CancelUnpaidOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $timeLimit = Carbon::now()->subMinutes(15);

        Order::where('payment_method', '!=', 'cod')
            ->where('payment_status', PaymentStatus::UNPAID)
            ->where('status', OrderStatus::PAYMENT_PENDING)
            ->where('created_at', '<', $timeLimit)
            ->chunkById(100, function ($orders) {

                foreach ($orders as $order) {

                    $misc = json_decode($order->misc, true) ?? [];

                    $misc['cancel_reason'] =
                        'Auto-cancelled due to payment timeout';

                    $order->update([
                        'status' => OrderStatus::CANCEL,
                        'misc' => json_encode($misc),
                    ]);
                    \App\Models\OrderHistory::create([
                        'order_id' => $order->id,
                        'previous_status' => OrderStatus::PAYMENT_PENDING,
                        'current_status' => OrderStatus::CANCEL,
                    ]);
                    // Coupon release
                    if ($order->coupon_id) {

                        Discount::where(
                            'order_id',
                            $order->id
                        )->delete();
                    }

                    Log::info(
                        "Order {$order->id} auto-cancelled."
                    );
                }
            });
    }
}