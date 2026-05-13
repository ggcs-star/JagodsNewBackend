<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use App\Http\Services\PushNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyNewOrderToAllDeliveryBoysJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle()
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        
        if ((int)$order->status !== 14) {
            return;
        }

        
        if ((int)$order->last_delivery_notification_status === 14) {
            return;
        }

        $deliveryBoys = User::role('Delivery Boy')
            ->where(function ($q) {
                $q->whereNotNull('device_token')
                  ->orWhereNotNull('web_token');
            })
            ->get();

        if ($deliveryBoys->isEmpty()) {
            Log::warning('No delivery boys found for notification', [
                'order_id' => $order->id,
            ]);
            return;
        }

        Log::info('STATUS 14 → Notify ALL Delivery Boys', [
            'order_id' => $order->id,
            'count'    => $deliveryBoys->count(),
        ]);

        foreach ($deliveryBoys as $deliveryBoy) {
            app(PushNotificationService::class)
                ->sendNewOrderNotificationToDeliveryBoy($order, $deliveryBoy);
        }

        
        $order->update([
            'last_delivery_notification_status' => 14
        ]);
    }
}