<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
     
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            padding: 20px; 
            color: #222; 
        }
        .header { text-align: center; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .invoice-details { margin-bottom: 20px; font-size: 14px; }
        .invoice-details p { margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #f9fafb; font-weight: bold; }
        .totals-table { width: 45%; float: right; margin-top: 20px; }
        .totals-table td { border-bottom: none; padding: 6px 10px; }
        .total-row { font-weight: bold; font-size: 1.2em; border-top: 2px solid #222; }
        .text-green { color: #16a34a; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>TAX INVOICE</h2>
    </div>

    <div class="invoice-details">
        <p><strong>Order ID:</strong> {{ $order->order_code }}</p>
        <p><strong>Date:</strong> {{ $order->created_at->format('d-M-Y h:i A') }}</p>
        <p><strong>Order Type:</strong> {{ $order->get_order_type }}</p>
    </div>

    <table>
        <tr>
            <th>Item</th>
            <th style="text-align: center;">Qty</th>
            <th class="text-right">Price</th>
        </tr>
        @foreach($order->orderLines as $item)
        <tr>
            <td>
                {{ $item->menuItem->name ?? 'Unknown Item' }}
                
                @if($item->variation)
                    <br><small style="color: #666;">({{ $item->variation->name ?? 'Variation' }})</small>
                @endif
            </td>
            <td style="text-align: center;">{{ $item->quantity }}</td>
            
            <td class="text-right">&#8377;{{ $item->item_total }}</td>
        </tr>
        @endforeach
    </table>

    <table class="totals-table">
        <tr>
            <td>Subtotal:</td>
            <td class="text-right">&#8377;{{ $order->sub_total }}</td>
        </tr>

        @if($order->discount > 0)
        <tr class="text-green">
            <td>Discount:</td>
            <td class="text-right">- &#8377;{{ $order->discount }}</td>
        </tr>
        @endif

        @if($order->packing_charge > 0)
        <tr>
            <td>Packaging Fee:</td>
            <td class="text-right">&#8377;{{ $order->packing_charge }}</td>
        </tr>
        @endif

        @if($order->platform_fee > 0)
        <tr>
            <td>Platform Fee:</td>
            <td class="text-right">&#8377;{{ $order->platform_fee }}</td>
        </tr>
        @endif

        @if($order->large_order_fee > 0)
        <tr>
            <td>Large Order Fee:</td>
            <td class="text-right">&#8377;{{ $order->large_order_fee }}</td>
        </tr>
        @endif

        @if($order->surge_fee > 0)
        <tr>
            <td>Surge Fee:</td>
            <td class="text-right">&#8377;{{ $order->surge_fee }}</td>
        </tr>
        @endif

        @if($order->tip_amount > 0)
        <tr>
            <td>Tip Amount:</td>
            <td class="text-right">&#8377;{{ $order->tip_amount }}</td>
        </tr>
        @endif

        <tr>
            <td>GST:</td>
            <td class="text-right">&#8377;{{ $order->gst_amount }}</td>
        </tr>

        <tr>
            <td>Delivery Fee:</td>
            <td class="text-right">&#8377;{{ $order->delivery_charge }}</td>
        </tr>

        <tr class="total-row">
            <td>Total Paid:</td>
            <td class="text-right">&#8377;{{ $order->total }}</td>
        </tr>
    </table>
</body>
</html>