<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $pdfContent;

    public function __construct(Order $order, $pdfContent)
    {
        $this->order = $order;
        $this->pdfContent = $pdfContent;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order Invoice - #' . $this->order->id,
        );
    }

    public function content(): Content
    {
        return new Content(
            // Ek chota sa email text jo user ko email open karte hi dikhega
            view: 'emails.order.invoice_text', 
        );
    }

    public function attachments(): array
    {
        return [
            // PDF ko memory se hi attach kar diya (Server storage bachane ke liye)
            Attachment::fromData(fn () => $this->pdfContent, 'Invoice_Order_' . $this->order->id . '.pdf')
                    ->withMime('application/pdf'),
        ];
    }
}