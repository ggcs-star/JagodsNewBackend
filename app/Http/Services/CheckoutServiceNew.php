<?php

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\DiscountStatus;
use App\Enums\PaymentMethod;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderLineItem;
use App\Models\OrderHistory;
use App\Models\Discount;
use App\Models\Restaurant; 
use App\Models\UserDevice; // 🚨 IMPORT ADDED
use App\Libraries\MyString;
use App\Jobs\SendPetpoojaOrderJob;
use App\Jobs\SendOrderNotificationsJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckoutServiceNew
{
    protected $validationService;
    protected $cartService;
    protected $invoiceService;

    public function __construct(
        CheckoutValidationService $validationService,
        CartService $cartService,
        InvoiceService $invoiceService
    ) {
        $this->validationService = $validationService;
        $this->cartService = $cartService;
        $this->invoiceService = $invoiceService;
    }

    // 🚨 1. Updated checkout signature to accept $device
    public function checkout(Cart $cart, int $paymentMethod, ?UserDevice $device = null)
    {
        return DB::transaction(function () use ($cart, $paymentMethod, $device) {

            $this->validationService->validate($cart);

            $this->cartService->updateCartTotals($cart);
            $cart->refresh();

            // 🚨 2. Pass $device to createOrder
            $order = $this->createOrder($cart, $paymentMethod, $device);

            $this->createOrderHistory($order);

            $this->createDiscountRecord($order, $cart);

            $this->createOrderItems($order, $cart);

            $this->invoiceService->generate($order);

            Restaurant::where('id', $order->restaurant_id)->increment('total_orders');

            if ($paymentMethod === PaymentMethod::CASH_ON_DELIVERY) {
                // SendPetpoojaOrderJob::dispatch($order->id)->afterCommit();
                // SendOrderNotificationsJob::dispatch($order->id)->afterCommit();
                Log::info("CheckoutServiceNew: COD Order {$order->id} placed. Jobs dispatched.");
            } else {
                Log::info("CheckoutServiceNew: Online Order {$order->id} initialized ID: {$paymentMethod}. Waiting for payment.");
            }

            return $order->fresh([]);
        });
    }

    // 🚨 3. Updated signature and added device handling
    private function createOrder(Cart $cart, int $paymentMethod, ?UserDevice $device): Order
    {
        $addressJson = "";
        $latitude = 0.0;
        $longitude = 0.0;

        if ($cart->address) {
            $latitude = $cart->address->latitude ?? 0.0;
            $longitude = $cart->address->longitude ?? 0.0;
            $addressJson = json_encode([
                'address' => $cart->address->address ?? '',
                'apartment' => $cart->address->apartment ?? ''
            ]);
        }

        $initialStatus = $paymentMethod === PaymentMethod::CASH_ON_DELIVERY ? OrderStatus::PENDING : OrderStatus::PAYMENT_PENDING;

        $order = Order::create([
            'user_id' => $cart->user_id,
            'user_device_id' => $device ? $device->id : null, // 🚨 4. DEVICE ID SAVED HERE
            'restaurant_id' => $cart->restaurant_id,
            'address_id' => $cart->address_id,
            'coupon_id' => $cart->coupon_id,
            'order_type' => $cart->order_type,
            'payment_method' => $paymentMethod,
            'payment_status' => PaymentStatus::UNPAID,
            'status' => $initialStatus,
            'address' => $addressJson,
            'lat' => $latitude,
            'long' => $longitude,
            'mobile' => $cart->user->phone ?? '',
            'sub_total' => $cart->subtotal,
            'discount' => $cart->discount,
            'gst_amount' => $cart->gst_amount,
            'delivery_charge' => $cart->delivery_charge,
            'packing_charge' => $cart->packing_charge ?? 0,
            'platform_fee' => $cart->platform_fee ?? 0,
            'large_order_fee' => $cart->large_order_fee ?? 0,
            'surge_fee' => $cart->surge_fee ?? 0,
            'tip_amount' => $cart->tip_amount ?? 0,
            'total' => $cart->total,
            'order_instructions' => $cart->order_instructions,
        ]);

        $order->misc = json_encode([
            'order_code' => 'ORD-' . MyString::code($order->id),
            'remarks' => $cart->order_instructions ?? '',
        ]);
        $order->save();

        return $order;
    }

    // ... (baki ke functions same rahenge)
    private function createOrderHistory(Order $order): void
    {
        OrderHistory::create([
            'order_id' => $order->id,
            'previous_status' => null,
            'current_status' => $order->status,
        ]);
    }

    private function createDiscountRecord(Order $order, Cart $cart): void
    {
        if (!blank($cart->coupon_id) && $cart->discount > 0) {
            Discount::create([
                'order_id' => $order->id,
                'coupon_id' => $cart->coupon_id,
                'user_id' => $cart->user_id,
                'amount' => $cart->discount,
                'status' => DiscountStatus::ACTIVE,
            ]);
        }
    }

    private function createOrderItems(Order $order, Cart $cart): void
    {
        $orderItems = [];

        foreach ($cart->items as $item) {
            $optionTotal = 0;
            $optionsArray = $item->options ?? [];
            if (!empty($optionsArray) && is_array($optionsArray)) {
                foreach ($optionsArray as $option) {
                    $optionTotal += (float) ($option['price'] ?? 0);
                }
            }

            $orderItems[] = [
                'order_id' => $order->id,
                'restaurant_id' => $order->restaurant_id,
                'menu_item_id' => $item->menu_item_id,
                'menu_item_variation_id' => $item->variation_id,
                'unit_price' => $item->unit_price,
                'discounted_price' => $item->discount_price,
                'quantity' => $item->quantity,
                'item_total' => $item->total_price,
                'options' => json_encode($optionsArray),
                'options_total' => $optionTotal,
                'instructions' => $item->instructions,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        OrderLineItem::insert($orderItems);
    }
}