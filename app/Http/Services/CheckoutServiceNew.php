<?php

namespace App\Http\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\DiscountStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderLineItem;
use App\Models\OrderHistory;
use App\Models\Discount;
use App\Libraries\MyString; // Purane logic se Order Code ke liye
use App\Jobs\SendPetpoojaOrderJob; // Background Jobs
use App\Jobs\SendOrderNotificationsJob; // Background Jobs
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

    public function checkout(Cart $cart, string $paymentMethod)
    {
        return DB::transaction(function () use ($cart, $paymentMethod) {

            // 1. Validate Cart
            $this->validationService->validate($cart);

            // 2. Update and lock latest totals
            $this->cartService->updateCartTotals($cart);
            $cart->refresh();

            // 3. Create Order (With Address Lat/Long & Order Code)
            $order = $this->createOrder($cart, $paymentMethod);

            // 4. Create Order History (Purane logic se)
            $this->createOrderHistory($order);

            // 5. Create Discount Record for Coupon (Purane logic se)
            $this->createDiscountRecord($order, $cart);

            // 6. Create Order Items (With Options Total)
            $this->createOrderItems($order, $cart);

            // 7. Generate Invoice
            $this->invoiceService->generate($order);

            // 8. Smart Logic for Push Notifications & Petpooja POS
            if ($paymentMethod === 'cod') {
                // Agar COD hai, toh order confirm hai. Turant restaurant aur user ko bata do (Background mein).
                SendPetpoojaOrderJob::dispatch($order->id)->afterCommit();
                SendOrderNotificationsJob::dispatch($order->id)->afterCommit();
                Log::info("CheckoutServiceNew: COD Order {$order->id} placed. Jobs dispatched.");
            } else {
                // Agar Online (Razorpay) hai, toh abhi chup raho. 
                // Notification tab jayega jab Webhook ya Verify endpoint isko PAID mark karega.
                Log::info("CheckoutServiceNew: Online Order {$order->id} initialized. Waiting for payment.");
            }

            return $order->fresh(['orderLines', 'restaurant', 'user']);
        });
    }

    private function createOrder(Cart $cart, string $paymentMethod): Order
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

   
        $initialStatus = $paymentMethod === 'cod' ? OrderStatus::PENDING : OrderStatus::PAYMENT_PENDING;

        $order = Order::create([
            'user_id' => $cart->user_id,
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
            'packing_charge' => $cart->packaging_charge ?? 0,
            'platform_fee' => $cart->platform_fee ?? 0,
            'large_order_fee' => $cart->large_order_fee ?? 0,
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