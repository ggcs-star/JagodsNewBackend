<?php

namespace App\Jobs;

use App\Models\Order;
use App\Http\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyOrderStatusToCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = Order::with('user')->find($this->orderId);

        if (!$order || !$order->user) {
            return;
        }

        $status = (int) $order->status;

        // allowed customer statuses
        $allowedStatuses = [15, 17, 20];

        if (!in_array($status, $allowedStatuses)) {
            return;
        }

        // duplicate notification prevent
        if ((int)$order->last_customer_notification_status === $status) {
            return;
        }

        Log::info('ORDER STATUS → Notify Customer', [
            'order_id' => $order->id,
            'customer_id' => $order->user->id,
            'status' => $status
        ]);

        app(PushNotificationService::class)
            ->sendOrderStatusNotificationToCustomer(
                $order,
                $order->user,
                $status
            );

        $order->update([
            'last_customer_notification_status' => $status
        ]);
    }
}