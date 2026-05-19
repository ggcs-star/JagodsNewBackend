<?php

namespace App\Http\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\MenuItemVariation;
use App\Enums\OrderTypeStatus;
use Exception;

class CheckoutValidationService
{
    public function validate(Cart $cart)
    {

        if (!$cart) {

            throw new Exception(
                'Cart not found',
                404
            );
        }

        if ($cart->items->count() <= 0) {

            throw new Exception(
                'Cart is empty',
                422
            );
        }

        $this->validateAddress($cart);

        $this->validateRestaurant($cart);

        $this->validateItems($cart);

        $this->validateCoupon($cart);

        return true;
    }


    private function validateAddress(Cart $cart)
    {
        if (
            $cart->order_type == OrderTypeStatus::DELIVERY
            &&
            !$cart->address_id
        ) {

            throw new Exception(
                'Delivery address missing',
                422
            );
        }
    }


    private function validateRestaurant(Cart $cart)
    {
        $restaurant = $cart->restaurant;

        if (!$restaurant) {
            throw new Exception(
                'Restaurant not found or has been removed.',
                404
            );
        }

        if ($restaurant->status != \App\Enums\Status::ACTIVE) {
            throw new Exception(
                "{$restaurant->name} is currently inactive and cannot accept orders.",
                422
            );
        }

        if ($cart->order_type == \App\Enums\OrderTypeStatus::DELIVERY) {
            if ($restaurant->delivery_status != \App\Enums\DeliveryStatus::ENABLE) {
                throw new Exception("Delivery is currently unavailable for {$restaurant->name}.", 422);
            }
        } elseif ($cart->order_type == \App\Enums\OrderTypeStatus::PICKUP) {
            if ($restaurant->pickup_status != \App\Enums\PickupStatus::ENABLE) {
                throw new Exception("Pickup is currently unavailable for {$restaurant->name}.", 422);
            }
        }

        if ($restaurant->current_status != \App\Enums\CurrentStatus::YES) {
            throw new Exception("{$restaurant->name} is temporarily not accepting any orders.", 422);
        }

        if (!$restaurant->is_open) {

            $openingTime = \Carbon\Carbon::parse($restaurant->opening_time)->format('h:i A');
            $closingTime = \Carbon\Carbon::parse($restaurant->closing_time)->format('h:i A');

            throw new Exception(
                "{$restaurant->name} is currently closed. Order timings are from {$openingTime} to {$closingTime}.",
                422
            );
        }
    }



    private function validateItems(Cart $cart)
    {
        foreach ($cart->items as $cartItem) {


            $menuItem = $cartItem->menuItem;

            if (!$menuItem) {
                $cartItem->update(['is_available' => false]);
                throw new Exception(
                    "{$cartItem->menu_name} is no longer available and has been removed.",
                    422
                );
            }

            if ($menuItem->status != 5) {
                $cartItem->update(['is_available' => false]);
                throw new Exception(
                    "{$cartItem->menu_name} is currently unavailable.",
                    422
                );
            }

            if ($cartItem->quantity > $menuItem->max_cart_quantity) {

                $cartItem->update([
                    'quantity' => $menuItem->max_cart_quantity,
                    'total_price' => $cartItem->price * $menuItem->max_cart_quantity,
                ]);

                throw new Exception(
                    "You can only order up to {$menuItem->max_cart_quantity} quantities of {$cartItem->menu_name}.",
                    422
                );
            }

            if ($cartItem->variation_id) {
                $variation = \App\Models\MenuItemVariation::find($cartItem->variation_id);

                if (!$variation) {
                    throw new Exception(
                        "The selected variation for {$cartItem->menu_name} is no longer available.",
                        422
                    );
                }
            }

            $livePrice = $this->getLivePrice($menuItem, $cartItem);

            if ($livePrice != $cartItem->price) {

                $cartItem->update([
                    'price' => $livePrice,
                    'total_price' => $livePrice * $cartItem->quantity,
                    'is_price_changed' => true,
                    'price_changed_at' => now(),
                ]);

                throw new Exception(
                    "The price for {$cartItem->menu_name} has changed. Please review your cart.",
                    422
                );
            }
        }
    }



    private function getLivePrice(
        $menuItem,
        $cartItem
    ) {

        if ($cartItem->variation_id) {

            $variation =
                MenuItemVariation::find(
                    $cartItem->variation_id
                );

            $price =
                $variation->price
                -
                $variation->discount_price;

        } else {

            $price =
                $menuItem->unit_price
                -
                $menuItem->discount_price;
        }



        if (
            !empty($cartItem->options)
            &&
            is_array($cartItem->options)
        ) {

            foreach (
                $cartItem->options
                as
                $option
            ) {

                $price +=
                    $option['price'] ?? 0;
            }
        }

        return round($price, 2);
    }



    private function validateCoupon(Cart $cart)
    {
        if (!$cart->coupon) {

            return;
        }

        $coupon = $cart->coupon;



        if (
            now()->lt($coupon->from_date)
            ||
            now()->gt($coupon->to_date)
        ) {

            $cart->update([
                'coupon_id' => null,
                'discount' => 0,
            ]);

            throw new Exception(
                'Coupon expired',
                422
            );
        }


        if ($coupon->limit <= 0) {

            $cart->update([
                'coupon_id' => null,
                'discount' => 0,
            ]);

            throw new Exception(
                'Coupon usage limit exceeded',
                422
            );
        }
    }
}