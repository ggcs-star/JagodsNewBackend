<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Order;
use App\Jobs\NotifyNewOrderToAllDeliveryBoysJob;
use App\Jobs\NotifyOrderStatusToCustomerJob;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
protected function schedule(Schedule $schedule): void
{
    $schedule->call(function () {

        /* =======================
           STATUS = 14 (ACCEPT)
           → Delivery Boy
        ======================= */
        Order::where('status', 14)
            ->where(function ($q) {
                $q->whereNull('last_delivery_notification_status')
                  ->orWhere('last_delivery_notification_status', '!=', 14);
            })
            ->pluck('id')
            ->each(fn ($id) =>
                NotifyNewOrderToAllDeliveryBoysJob::dispatch($id)
            );


        /* =======================
           STATUS = 15,17,20
           → Customer
        ======================= */
       Log::info('CRON: Checking customer order notifications');

Order::whereIn('status', [15,17,20])
    ->where(function ($q) {
        $q->whereNull('last_customer_notification_status')
          ->orWhereColumn('status', '!=', 'last_customer_notification_status');
    })
    ->pluck('id')
    ->each(function ($id) {

        Log::info('CRON: Dispatching NotifyOrderStatusToCustomerJob', [
            'order_id' => $id
        ]);

        NotifyOrderStatusToCustomerJob::dispatch($id);

    });

    })->everyMinute();
}




    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
