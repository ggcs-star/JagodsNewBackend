<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderInvoiceMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;

class SendOrderInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public $tries = 3;

    public $backoff = 60;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle(): void
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        if (isset($settings['mail_host'])) {
            config([
                'mail.mailers.smtp.host'       => $settings['mail_host'],
                'mail.mailers.smtp.port'       => $settings['mail_port'],
                'mail.mailers.smtp.username'   => $settings['mail_username'],
                'mail.mailers.smtp.password'   => $settings['mail_password'],
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'] ?? 'tls', 
                'mail.from.address'            => $settings['mail_from_address'],
                'mail.from.name'               => $settings['mail_from_name'],
            ]);
        }

        $pdf = Pdf::loadView('emails.order.pdf_invoice', ['order' => $this->order]);

        Mail::to($this->order->user->email)->send(new OrderInvoiceMail($this->order, $pdf->output()));

        Log::info("Invoice sent successfully for Order: " . $this->order->id);
    }

 
    public function failed(\Throwable $exception): void
    {
        Log::error("SendOrderInvoiceJob Permanently Failed for Order " . $this->order->id . " Reason: " . $exception->getMessage());
    }
}