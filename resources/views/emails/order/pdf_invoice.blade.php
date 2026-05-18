<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .total-row { font-weight: bold; }
    </style>
</head>
<body>
    <h2>TAX INVOICE</h2>
    <p><strong>Order ID:</strong> #{{ $order->id }}</p>
    <p><strong>Date:</strong> {{ $order->created_at->format('d-M-Y H:i A') }}</p>

    <table>
        <tr>
            <th>Item</th>
            <th>Qty</th>
            <th>Price</th>
        </tr>
        @foreach($order->orderLines as $item)
        <tr>
            <td>{{ $item->menu_name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₹{{ $item->total_price }}</td>
        </tr>
        @endforeach
    </table>

    <table style="width: 50%; float: right; margin-top: 20px;">
        <tr><td>Subtotal:</td><td>₹{{ $order->subtotal }}</td></tr>
        <tr><td>GST:</td><td>₹{{ $order->gst_amount }}</td></tr>
        <tr><td>Delivery:</td><td>₹{{ $order->delivery_charge }}</td></tr>
        <tr class="total-row"><td>Total Paid:</td><td>₹{{ $order->total }}</td></tr>
    </table>
</body>
</html>