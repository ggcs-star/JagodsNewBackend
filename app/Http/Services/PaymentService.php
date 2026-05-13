<?php

namespace App\Http\Services;

use App\Enums\OrderTypeStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\Restaurant;

class PaymentService
{
    public $data = array();

    public function payment($paymetSuccess)
    {

        $restaurant = Restaurant::find(session('session_cart_restaurant_id'));
        $request = session()->get('checkoutRequest');

        $cart = session()->get('cart');

        if (($cart && isset($cart['delivery_type']) && $cart['delivery_type'] == true) || ($cart && isset($cart['free_delivery']) && $cart['free_delivery'])) {
            $delivery_charge = 0;
            $order_type = OrderTypeStatus::PICKUP;
        } else {
            $delivery_charge = session()->get('delivery_charge');
            $order_type = OrderTypeStatus::DELIVERY;
        }

        $items = [];

        $cartItems = session()->get('cart')['items'] ?? [];

        foreach ($cartItems as $cart) {
            $menuItemVariationId = $cart['variation']['id'] ?? null;
            $variation = $cart['variation'] ?? null;
            $options = $cart['options'] ?? null;
            $instructions = $cart['instructions'] ?? null;

            $items[] = [
                'restaurant_id' => $restaurant->id,
                'menu_item_variation_id' => $menuItemVariationId,
                'menu_item_id' => $cart['menuItem_id'],
                'unit_price' => (float) $cart['price'],
                'quantity' => (int) $cart['qty'],
                'discounted_price' => (float) $cart['discount'],
                'variation' => $variation,
                'options' => $options,
                'instructions' => $instructions,
            ];
        }

        if ($request['payment_type'] == PaymentMethod::STRIPE && $paymetSuccess) {
            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } elseif ($request['payment_type'] == PaymentMethod::PAYTM) {
            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;

        } elseif ($request['payment_type'] == PaymentMethod::PHONEPE) {
            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;

        } elseif ($request['payment_type'] == PaymentMethod::WALLET) {

            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } elseif ($request['payment_type'] == PaymentMethod::PAYSTACK && $paymetSuccess) {

            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } elseif ($request['payment_type'] == PaymentMethod::PAYPAL && $paymetSuccess) {

            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } elseif ($request['payment_type'] == PaymentMethod::RAZORPAY && $paymetSuccess) {
            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } elseif ($request['payment_type'] == PaymentMethod::SSLCOMMERZ) {
            $this->data['paid_amount'] = session()->get('cart')['totalAmount'] + $delivery_charge;
            $this->data['payment_method'] = $request['payment_type'];
            $this->data['payment_status'] = PaymentStatus::PAID;
        } else {
            $this->data['paid_amount'] = 0;
            $this->data['payment_method'] = PaymentMethod::CASH_ON_DELIVERY;
            $this->data['payment_status'] = PaymentStatus::UNPAID;
        }

        $cart = session()->get('cart');

        $this->data['coupon_id'] = isset($cart['couponID']) ? $cart['couponID'] : null;
        $this->data['coupon_amount'] = isset($cart['coupon_amount']) ? $cart['coupon_amount'] : null;

        $this->data['items'] = $items;
        $this->data['order_type'] = $order_type;
        $this->data['restaurant_id'] = session('session_cart_restaurant_id');
        $this->data['user_id'] = auth()->user()->id;
        $this->data['total'] = isset($cart['totalAmount']) ? $cart['totalAmount'] : 0;
        $this->data['delivery_charge'] = $delivery_charge;
        $this->data['address'] = isset($request['address']) ? $request['address'] : '';
        $this->data['mobile'] = $request['countrycode'] . $request['mobile'];
        $orderService = app(OrderService::class)->order($this->data);

        return $orderService;
    }
}
