<?php

namespace App\Http\Services;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Str;

class InvoiceService
{
    public function generate(Order $order)
    {
        $invoiceId = Str::uuid();

        $invoice = Invoice::create([

            'id' => $invoiceId,

            'meta' => [

                'order_id' =>
                    $order->id,

                'amount' =>
                    $order->total,

                'user_id' =>
                    $order->user_id,
            ]
        ]);

        $order->update([
            'invoice_id' => $invoiceId
        ]);

        return $invoice;
    }
}