<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\Balance;
use App\Models\Discount;
use App\Models\MenuItem;
use App\Enums\OrderStatus;
use App\Models\Restaurant;
use App\Libraries\MyString;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\OrderHistory;
use App\Enums\DiscountStatus;
use App\Models\OrderLineItem;
use App\Enums\OrderTypeStatus;
use App\Models\DeliveryBoyAccount;
use App\Enums\ProductReceiveStatus;
use App\Models\BestSellingCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class OrderService
{
    public $adminBalanceId = 1;
    public $commission     = 0;

    public function __construct()
    {
        $this->commission = setting('order_commission_percentage');
    }

    public function accept(int $orderId)
    {
        $order = Order::where([
            'status' => OrderStatus::PENDING,
            'id'     => $orderId,
        ])->first();
        if (!blank($order)) {
            if ($order->payment_status == PaymentStatus::UNPAID) {
                $balance = Balance::find($order->user->balance_id);
                if (!blank($balance) && $balance->balance >= $order->total) {
                    $payment = app(TransactionService::class)->payment($order->user->balance_id, $this->adminBalanceId, $order->total, $orderId);
                    if ($payment->status) {
                        $order->payment_status = PaymentStatus::PAID;
                        $order->payment_method = PaymentMethod::WALLET;
                        $order->paid_amount    = $order->total;
                    }
                }
            }
            $order->status = OrderStatus::ACCEPT;
            $order->save();
            $orderHistory = $this->status($orderId, OrderStatus::ACCEPT);
            if ($orderHistory->status) {
                ResponseService::set(['order_history_id' => $orderHistory->order_history_id]);
            }
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        } else {
            ResponseService::set([
                'status'  => false,
                'message' => 'Order not found',
            ]);
        }
        return ResponseService::response();
    }

    public function reject(int $orderId)
    {
        $order = Order::where([
            'status' => OrderStatus::PENDING,
            'id'     => $orderId,
        ])->first();
        if (!blank($order)) {
            if ($order->payment_status == PaymentStatus::PAID && $order->payment_method != PaymentMethod::CASH_ON_DELIVERY) {
                $refund = app(TransactionService::class)->refund($this->adminBalanceId, $order->user->balance_id, $order->total, $orderId);
                if ($refund->status) {
                    $order->payment_status = PaymentStatus::UNPAID;
                    $order->payment_method = PaymentMethod::CASH_ON_DELIVERY;
                    $order->paid_amount    = 0;
                }
            }
            $order->status = OrderStatus::REJECT;
            $order->save();

            $couponStatus = Discount::where('order_id', $orderId)->first();
            if (!blank($couponStatus)) {
                $couponStatus->status = DiscountStatus::CANCELED;
            }

            $orderHistory = $this->status($orderId, OrderStatus::REJECT);
            if ($orderHistory->status) {
                ResponseService::set([
                    'order_history_id' => $orderHistory->order_history_id,
                ]);
            }
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        } else {
            ResponseService::set([
                'status'  => false,
                'message' => 'Order not found',
            ]);
        }
        return ResponseService::response();
    }

    public function cancel(int $orderId) // done
    {
        $order = Order::where([
            'status' => OrderStatus::PENDING,
            'id'     => $orderId,
        ])->first();
        if (!blank($order)) {
            if ($order->payment_status == PaymentStatus::PAID && $order->payment_method != PaymentMethod::CASH_ON_DELIVERY) {
                $refund = app(TransactionService::class)->refund($this->adminBalanceId, $order->user->balance_id, $order->total, $orderId);
                if ($refund->status) {
                    $order->payment_status = PaymentStatus::UNPAID;
                    $order->payment_method = PaymentMethod::CASH_ON_DELIVERY;
                    $order->paid_amount    = 0;
                }
            }
            $order->status = OrderStatus::CANCEL;
            $order->save();

            $couponStatus = Discount::where('order_id', $orderId)->first();
            if (!blank($couponStatus)) {
                $couponStatus->status = DiscountStatus::CANCELED;
            }

            $orderHistory = $this->status($orderId, OrderStatus::CANCEL);
            if ($orderHistory->status) {
                ResponseService::set([
                    'order_history_id' => $orderHistory->order_history_id,
                ]);
            }
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        } else {
            ResponseService::set([
                'status'  => false,
                'message' => 'Order not found',
            ]);
        }
        return ResponseService::response();
    }

    public function process(int $orderId) // done

    {
        $order = Order::where([
            'status' => OrderStatus::ACCEPT,
            'id'     => $orderId,
        ])->first();
        if (!blank($order)) {
            $order->status = OrderStatus::PROCESS;
            $order->save();
            $orderHistory = $this->status($orderId, OrderStatus::PROCESS);
            if ($orderHistory->status) {
                ResponseService::set(['order_history_id' => $orderHistory->order_history_id]);
            }
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        } else {
            ResponseService::set([
                'status'  => false,
                'message' => 'Order not found',
            ]);
        }
        return ResponseService::response();
    }

    public function completed(int $orderId) //done
    {
        $order = Order::orderowner()->findOrFail($orderId);
        if (!blank($order)) {
            if($order->order_type == OrderTypeStatus::DELIVERY){
                if ($order->payment_method == PaymentMethod::CASH_ON_DELIVERY && $order->payment_status == PaymentStatus::UNPAID) {
                    $addFund = app(TransactionService::class)->addFund(0, $order->user->balance_id, PaymentMethod::CASH_ON_DELIVERY, $order->total, $orderId);
                    if ($addFund->status) {
                        $payment = app(TransactionService::class)->payment($order->user->balance_id, $this->adminBalanceId, $order->total, $orderId);
                        if ($payment->status) {
                            $order->paid_amount    = $order->total;
                            $order->payment_status = PaymentStatus::PAID;
                        } else {
                            ResponseService::set([
                                'status'   => false,
                                'message'  => 'Payment does not complete',
                                'order_id' => $orderId,
                            ]);
                        }
                    }

                    $deliveryBoyAccount = DeliveryBoyAccount::where('user_id', $order->delivery_boy_id)->first();
                    if (!blank($deliveryBoyAccount)) {
                        $deliveryBoyAccount->delivery_charge = $deliveryBoyAccount->delivery_charge + $order->delivery_charge;
                        $deliveryBoyAccount->balance         = $deliveryBoyAccount->balance + $order->total;
                        $deliveryBoyAccount->save();
                    } else {
                        ResponseService::set([
                            'status'   => false,
                            'message'  => 'Delivery boy account does not found',
                            'order_id' => $orderId,
                        ]);
                    }
                } else {
                    $deliveryBoy = User::find($order->delivery_boy_id);
                    if (!blank($deliveryBoy)) {
                        $deliveryBoyBalanceId = $deliveryBoy->balance_id;
                        $amount               = $order->delivery_charge;
                        $transfer             = app(TransactionService::class)->transfer($this->adminBalanceId, $deliveryBoyBalanceId, $amount, $orderId);
                        if ($transfer->status) {
                            ResponseService::set([
                                'status'   => true,
                                'order_id' => $orderId,
                                'amount'   => $amount,
                            ]);
                        } else {
                            ResponseService::set([
                                'status'   => false,
                                'message'  => 'Delivery boy payment fail',
                                'order_id' => $orderId,
                                'amount'   => $amount,
                            ]);
                        }
                    } else {
                        ResponseService::set([
                            'status'   => false,
                            'message'  => 'Delivery boy not found',
                            'order_id' => $orderId,
                        ]);
                    }
                }

            }elseif($order->order_type == OrderTypeStatus::PICKUP) {

                if ($order->payment_method == PaymentMethod::CASH_ON_DELIVERY && $order->payment_status == PaymentStatus::UNPAID) {
                    $addFund = app(TransactionService::class)->addFund(0, $order->user->balance_id, PaymentMethod::CASH_ON_DELIVERY, $order->total, $orderId);
                    if ($addFund->status) {
                        $payment = app(TransactionService::class)->payment($order->user->balance_id, 0, $order->total, $orderId);
                        if ($payment->status) {
                            $order->paid_amount    = $order->total;
                            $order->payment_status = PaymentStatus::PAID;

                            $restaurant          = Restaurant::find($order->restaurant_id);
                            $restaurantBalanceId = !blank($restaurant) ? $restaurant->user->balance_id : 0;
                            $amount              = ($order->sub_total / 100) * $this->commission;
                            $transfer            = app(TransactionService::class)->transfer($restaurantBalanceId, $this->adminBalanceId,  $amount, $orderId);
                        } else {
                            ResponseService::set([
                                'status'   => false,
                                'message'  => 'Payment does not complete',
                                'order_id' => $orderId,
                            ]);
                        }
                    }
                } else {
                    $restaurant          = Restaurant::find($order->restaurant_id);
                    $restaurantBalanceId = !blank($restaurant) ? $restaurant->user->balance_id : 0;
                    $amount              = ($order->sub_total - ($order->sub_total / 100) * $this->commission);
                    $transfer            = app(TransactionService::class)->transfer($this->adminBalanceId, $restaurantBalanceId, $amount, $orderId);
                }

            }


            $order->status = OrderStatus::COMPLETED;
            $order->save();

            $bestSelling = $this->bestSelling($orderId);
            if ($bestSelling->status) {
                ResponseService::set([
                    'status'   => true,
                    'order_id' => $orderId,
                ]);
            } else {
                ResponseService::set([
                    'status'   => false,
                    'message'  => $bestSelling->message,
                    'order_id' => $orderId,
                ]);
            }
            $orderHistory = $this->status($orderId, OrderStatus::COMPLETED);
            if ($orderHistory->status) {
                ResponseService::set([
                    'order_history_id' => $orderHistory->order_history_id,
                ]);
            }
        }
        return ResponseService::response();
    }

    public function productReceive(int $orderId, int $productReceiveStatus) // done
    {
        $order = Order::where([
            'status' => OrderStatus::PROCESS,
            'id'     => $orderId,
        ])->first();
        if (!blank($order)) {
            if ($productReceiveStatus == ProductReceiveStatus::RECEIVE) {
                $restaurant          = Restaurant::find($order->restaurant_id);
                $restaurantBalanceId = !blank($restaurant) ? $restaurant->user->balance_id : 0;
                $amount        = ($order->sub_total - ($order->sub_total / 100) * $this->commission);
                $transfer      = app(TransactionService::class)->transfer($this->adminBalanceId, $restaurantBalanceId, $amount, $orderId);

                if ($transfer->status) {
                    $order->status = OrderStatus::ON_THE_WAY;
                    $orderHistory  = $this->status($orderId, OrderStatus::ON_THE_WAY);
                    if ($orderHistory->status) {
                        ResponseService::set(['order_history_id' => $orderHistory->order_history_id]);
                    }
                }
            }
            $order->product_received = $productReceiveStatus;

            $order->save();
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        } else {
            ResponseService::set([
                'status'  => false,
                'message' => 'Order not found',
            ]);
        }
        return ResponseService::response();
    }

    public function status(int $orderId, int $status) // done
    {
        $orderHistory = OrderHistory::where(['order_id' => $orderId])->latest()->first();
        if (!blank($orderHistory)) {
            if ($orderHistory->current_status != $status) {
                $orderHistory = OrderHistory::create([
                    'order_id'        => $orderId,
                    'previous_status' => $orderHistory->current_status,
                    'current_status'  => $status,
                ]);
            }
        } else {
            $orderHistory = OrderHistory::create([
                'order_id'        => $orderId,
                'previous_status' => null,
                'current_status'  => OrderStatus::PENDING,
            ]);
            $orderHistory = OrderHistory::create([
                'order_id'        => $orderId,
                'previous_status' => $orderHistory->current_status,
                'current_status'  => $status,
            ]);
        }
        ResponseService::set([
            'status'           => true,
            'order_history_id' => $orderHistory->id,
        ]);
        return ResponseService::response();
    }

    public function bestSelling(int $orderId)
    {
        $orderItems = OrderLineItem::where(['order_id' => $orderId])->get();
        if (!blank($orderItems)) {
            foreach ($orderItems as $item) {
                $bestProduct = MenuItem::where([
                    'id' => $item->menu_item_id,
                    'restaurant_id'    => $item->restaurant_id,
                ])->first();
                if ($bestProduct) {
                    $bestProduct->counter = $bestProduct->counter + $item->quantity;
                    $bestProduct->save();
                }
                if (!blank($item->menuItem->categories)) {
                    foreach ($item->menuItem->categories as $category) {
                        $bestCategory = BestSellingCategory::where([
                            'category_id' => $category->id,
                            'restaurant_id'     => $item->restaurant_id,
                        ])->first();
                        if ($bestCategory) {
                            $bestCategory->counter = $bestCategory->counter + $item->quantity;
                            $bestCategory->save();
                        } else {
                            BestSellingCategory::create([
                                'category_id' => $category->id,
                                'restaurant_id'     => $item->restaurant_id,
                                'counter'     => $item->quantity,
                            ]);
                        }
                    }
                }
            }
            ResponseService::set([
                'status'   => true,
                'order_id' => $orderId,
            ]);
        }
        return ResponseService::response();
    }

    

public function order($data): object
{
    Log::info('OrderService::order started', [
        'user_id' => $data['user_id'] ?? null,
        'restaurant_id' => $data['restaurant_id'] ?? null
    ]);

    $address = "";
    $latitude = "";
    $longitude = "";

    if (!empty($data['addressLabel'])) {

        Log::info('Address label detected', [
            'addressLabel' => $data['addressLabel']
        ]);

        if ($data['addressLabel'] == 'current') {

            Log::info('Using current location', [
                'lat' => $data['lat'] ?? null,
                'long' => $data['long'] ?? null
            ]);

            $latitude = $data['lat'];
            $longitude = $data['long'];
            $address = json_encode([
                'address' => $data['address'],
                'apartment' => ""
            ]);

        } else {

            Log::info('Fetching address by label', [
                'label_name' => $data['addressLabel']
            ]);

            $address = Address::where('label_name', $data['addressLabel'])->first();

            Log::info('Address fetched', [
                'address_found' => $address ? true : false
            ]);

            $latitude = $address->latitude;
            $longitude = $address->longitude;

            $address = json_encode([
                'address' => $address->address,
                'apartment' => $address->apartment
            ]);
        }

    } else {

        Log::info('Address label not provided');

        if ($data['address'] == '') {

            Log::info('Empty address received');

            $latitude = 0.0;
            $longitude = 0.0;
            $address = json_encode([
                'address' => '',
                'apartment' => ''
            ]);

        } else {

            Log::info('Fetching address by ID', [
                'address_id' => $data['address']
            ]);

            $address = Address::find($data['address']);

            Log::info('Address fetched by ID', [
                'address_found' => $address ? true : false
            ]);

            $latitude = $address->latitude;
            $longitude = $address->longitude;

            $address = json_encode([
                'address' => $address->address,
                'apartment' => $address->apartment
            ]);
        }
    }

    Log::info('Order address prepared', [
        'latitude' => $latitude,
        'longitude' => $longitude
    ]);
    
$subTotal   = $data['sub_total'];   
$totalAmount = $data['total'];     

$gstAmount = round(($subTotal * 5) / 100, 2); 



    $order = [
        'user_id'         => $data['user_id'],
        'restaurant_id'   => $data['restaurant_id'],
         'total'           => $totalAmount,
       'sub_total'       => $subTotal,
        'gst_amount'      => $gstAmount,
        'delivery_charge' => $data['delivery_charge'],
        'status'          => OrderStatus::PENDING,
        'order_type'      => $data['order_type'],
        'address'         => $address,
        'mobile'          => $data['mobile'],
        'lat'             => $latitude,
        'long'            => $longitude,
        'misc'            => json_encode(["remarks" => '']),
        'payment_method'  => $data['payment_method'],
        'payment_status'  => $data['payment_status'],
        'paid_amount'     => $data['paid_amount'],
        'discount'         => $data['discount'],
        'search_fee'         => $data['search_fee'],
        'handling_fee'         => $data['handling_fee'],
        'packaging_fee'         => $data['packaging_fee'],
    ];

    Log::info('Creating order record');

    $order   = Order::create($order);
    $orderId = $order->id;

    Log::info('Order created', [
        'order_id' => $orderId
    ]);

    OrderHistory::create([
        'order_id'        => $orderId,
        'previous_status' => null,
        'current_status'  => OrderStatus::PENDING,
    ]);

    Log::info('Order history created');

    if (!blank($data['coupon_id'])) {

        Log::info('Applying coupon', [
            'coupon_id' => $data['coupon_id']
        ]);

        Discount::create([
            'order_id'  => $orderId,
            'coupon_id' => $data['coupon_id'],
            'user_id'   => auth()->user()->id,
            'amount'    => $data['coupon_amount'],
            'status'    => DiscountStatus::ACTIVE,
        ]);
    }

    if (!blank($data['items'])) {

        Log::info('Processing order items', [
            'items_count' => count($data['items'])
        ]);

        $i = 0;
        $orderLineItems = [];

        foreach ($data['items'] as $item) {

            Log::info('Processing item', [
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity']
            ]);

            $optionTotal = 0;

            if (isset($item['options']) && !blank($item['options'])) {

                foreach ($item['options'] as $option) {
                    $optionTotal += $option['price'];
                }
            }

            $orderLineItems[$i] = [
                'order_id'                  => $orderId,
                'restaurant_id'             => $item['restaurant_id'],
                'menu_item_id'              => $item['menu_item_id'],
                'quantity'                  => $item['quantity'],
                'unit_price'                => $item['unit_price'],
                'discounted_price'          => $item['discounted_price'],
                'item_total'                => ($item['unit_price'] * $item['quantity']),
                'menu_item_variation_id'    => $item['menu_item_variation_id'],
                'options'                   => json_encode($item['options']),
                'instructions'              => $item['instructions'],
                'options_total'             => $optionTotal,
                'created_at'                => date('Y-m-d H:i:s'),
                'updated_at'                => date('Y-m-d H:i:s'),
            ];

            $i++;
        }

        Log::info('Inserting order line items');

        OrderLineItem::insert($orderLineItems);

        ResponseService::set([
            'status'   => true,
            'order_id' => $orderId,
        ]);

    } else {

        Log::warning('Items not found in order request');

        ResponseService::set(['message' => 'Items not found']);
    }

    $order = Order::findOrFail($orderId);

    Log::info('Generating order code');

    $order->misc = json_encode([
        'order_code' => 'ORD-' . MyString::code($orderId),
        'remarks'    => isset($data['remarks']) ? $data['remarks'] : '',
    ]);

    $order->save();

    if ($data['payment_status'] == PaymentStatus::PAID) {

        Log::info('Processing payment');

        if ($data['payment_method'] != PaymentMethod::WALLET) {

            Log::info('Adding fund transaction');

            $addFund = app(TransactionService::class)->addFund(
                0,
                $order->user->balance_id,
                $data['payment_method'],
                $order->total,
                $orderId
            );
        }

        if ($this->adminBalanceId != $order->user->balance_id) {

            Log::info('Transferring payment to admin');

            app(TransactionService::class)->payment(
                $order->user->balance_id,
                $this->adminBalanceId,
                $order->total,
                $orderId
            );
        }
    }

    Log::info('OrderService::order completed', [
        'order_id' => $orderId
    ]);

    return ResponseService::response();
}

    public function orderUpdate(int $orderId, int $status)
    {
        $orderStatus = (object) [
            'status'  => false,
            'message' => 'Not found',
        ];
        if ($status == OrderStatus::ACCEPT) {
            $orderStatus = $this->accept($orderId);
        } elseif ($status == OrderStatus::REJECT) {
            $orderStatus = $this->reject($orderId);
        } elseif ($status == OrderStatus::CANCEL) {
            $orderStatus = $this->cancel($orderId);
        } elseif ($status == OrderStatus::PROCESS) {
            $orderStatus = $this->process($orderId);
        } elseif ($status == OrderStatus::COMPLETED) {
            $orderStatus = $this->completed($orderId);
        }

        if ($orderStatus->status) {
            ResponseService::set([
                'status'           => $orderStatus->status,
                'order_id'         => $orderStatus->order_id,
                'order_history_id' => $orderStatus->order_history_id,
            ]);
        }
        return ResponseService::response();
    }
}
