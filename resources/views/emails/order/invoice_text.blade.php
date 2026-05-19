<p>Hi {{ $order->user->name ?? 'Customer' }},</p>
<p>Thank you for your order! Your payment was successful.</p>
<p>Please find the attached PDF invoice for your Order #{{ $order->id }}.</p>
<p>Enjoy your meal!</p>