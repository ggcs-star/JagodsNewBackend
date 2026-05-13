<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\FrontendController;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\MenuItem;
use App\Models\MenuItemOption;
use App\Models\MenuItemVariation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Discount;
use App\Enums\DiscountStatus;
use App\Enums\OrderTypeStatus;
use App\Enums\CouponType;
use App\Models\Restaurant;
use App\Models\Setting;
class CartController extends FrontendController
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth:api');
        $this->data['site_title'] = 'Frontend';
    }

    public function index()
    {
        $userId = auth()->id();
        $cart = Cart::with([
            'coupon',
            'items.menuItem',
            'items.variation'
        ])
            ->where('user_id', $userId)
            ->first();

        if (!$cart) {

            return $this->successresponse([
                'status' => 200,
                'message' => 'Cart is empty',
                'data' => [
                    'cart_id' => null,
                    'user_id' => $userId,
                    'order_type' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'cart_items_count' => 0,
                    'subtotal' => 0,
                    'discount' => 0,
                    'gst_amount' => 0,
                    'delivery_charge' => 0,
                    'total' => 0,
                    'coupon' => null,
                    'items' => [],
                ]
            ]);
        }

        $this->updateCartTotals($cart);
        $cart->refresh();
        $items = $cart->items->map(function ($item) {
            $menuItem = $item->menuItem;
            $variation = $item->variation;

            return [
                'cart_item_id' => $item->id,
                'menu_item_id' => $item->menu_item_id,
                'menu_name' => optional($menuItem)->name,
                'menu_slug' => optional($menuItem)->slug,
                'menu_image' => optional($menuItem)->image,
                'restaurant_id' => optional($menuItem)->restaurant_id,
                'restro_type' => optional($menuItem)->restroType,
                'variation' => $variation ? [
                    'id' => $variation->id,
                    'name' => $variation->name,
                    'price' => (float) $variation->price,
                    'discount_price' => (float) $variation->discount_price,
                ] : null,
                'options' => $item->options ?? [],
                'instructions' => $item->instructions,
                'quantity' => (int) $item->quantity,
                'price' => (float) $item->price,
                'total_price' => (float) $item->total_price,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        });


        return $this->successresponse([

            'status' => 200,
            'message' => 'Cart fetched successfully',

            'data' => [
                'cart_id' => $cart->id,
                'user_id' => $cart->user_id,
                'coupon_id' => $cart->coupon_id,
                'restaurant_id' => $cart->restaurant_id,
                'order_type' => $cart->order_type,
                'latitude' => $cart->latitude,
                'longitude' => $cart->longitude,
                'cart_items_count' => $cart->items->count(),
                'subtotal' => (float) $cart->subtotal,
                'discount' => (float) $cart->discount,
                'gst_amount' => (float) $cart->gst_amount,
                'delivery_charge' => (float) $cart->delivery_charge,
                'total' => (float) $cart->total,
                'coupon' => $cart->coupon ? [
                    'id' => $cart->coupon->id,
                    'name' => $cart->coupon->name,
                    'code' => $cart->coupon->slug,
                    'discount_type' => $cart->coupon->discount_type,
                    'amount' => (float) $cart->coupon->amount,
                    'minimum_order_amount' => (float) $cart->coupon->minimum_order_amount,
                    'from_date' => $cart->coupon->from_date,
                    'to_date' => $cart->coupon->to_date,

                ] : null,
                'items' => $items,
                'created_at' => $cart->created_at,
                'updated_at' => $cart->updated_at,
            ]
        ]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'menu_id' => 'required|numeric',
            'variation_id' => 'nullable|numeric',
            'options' => 'nullable|array',
            'options.*' => 'nullable|numeric',
            'instructions' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {

            return $this->successresponse([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }

        $userId = auth()->id();

        $menuItem = MenuItem::find($request->menu_id);

        if (!$menuItem) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Menu item not found'
            ]);
        }

        $cart = Cart::firstOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'coupon_id' => null,
                'subtotal' => 0,
                'restaurant_id' => $menuItem->restaurant_id,
                'discount' => 0,
                'order_type' => OrderTypeStatus::DELIVERY,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'gst_amount' => 0,
                'delivery_charge' => 0,
                'total' => 0,
            ]
        );


        if ($request->filled('latitude') && $request->filled('longitude')) {

            $cart->update([
                'restaurant_id' => $menuItem->restaurant_id,
                'latitude' => $request->latitude,

                'longitude' => $request->longitude,
            ]);
        }

        $variationId = null;

        if ($request->variation_id) {

            $variation = MenuItemVariation::find($request->variation_id);

            if (!$variation) {

                return $this->successresponse([
                    'status' => 404,
                    'message' => 'Variation not found'
                ]);
            }

            $variationId = $variation->id;

            $price = $variation->price - $variation->discount_price;

        } else {

            $price = $menuItem->unit_price - $menuItem->discount_price;
        }

        $optionArray = [];

        if (!blank($request->options)) {

            $options = MenuItemOption::whereIn('id', $request->options)->get();

            foreach ($options as $option) {

                $optionArray[] = [
                    'id' => $option->id,
                    'name' => $option->name,
                    'price' => $option->price,
                ];

                $price += $option->price;
            }
        }

        $quantity = $request->quantity ?? 1;

        $totalPrice = $price * $quantity;

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('menu_item_id', $menuItem->id)
            ->where('variation_id', $variationId)
            ->first();

        if ($existingItem) {

            $newQty = $existingItem->quantity + $quantity;

            $existingItem->update([
                'quantity' => $newQty,
                'price' => $price,
                'total_price' => $price * $newQty,
                'instructions' => $request->instructions,
                'options' => $optionArray,
            ]);

        } else {

            CartItem::create([
                'cart_id' => $cart->id,
                'menu_item_id' => $menuItem->id,
                'variation_id' => $variationId,
                'options' => $optionArray,
                'instructions' => $request->instructions,
                'quantity' => $quantity,
                'price' => $price,
                'total_price' => $totalPrice,
            ]);
        }

        $this->updateCartTotals($cart);

        return $this->successresponse([
            'status' => 200,
            'message' => 'Added to cart successfully',
            'cart' => $cart->fresh([
                'items.menuItem',
                'items.variation',
                'coupon'
            ])
        ]);
    }
    public function updateLocation(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'latitude' => 'required|numeric',

            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            return $this->successresponse([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }

        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Cart not found'
            ]);
        }

        $cart->update([

            'latitude' => $request->latitude,

            'longitude' => $request->longitude,
        ]);

        $this->updateCartTotals($cart);

        return $this->successresponse([

            'status' => 200,

            'message' => 'Location updated successfully',

            'cart' => $cart->fresh([
                'items.menuItem',
                'items.variation',
                'coupon'
            ])
        ]);
    }
    public function updateOrderType(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'order_type' => 'required|in:1,2,3',
        ]);

        if ($validator->fails()) {

            return $this->successresponse([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }

        $cart = Cart::where('user_id', auth()->id())->first();

        if (!$cart) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Cart not found'
            ]);
        }

        $deliveryCharge = (float) ($settings['basic_delivery_charge'] ?? 0);

        if ($request->order_type == OrderTypeStatus::DELIVERY) {


            $deliveryCharge = (float) ($settings['basic_delivery_charge'] ?? 0);
        }

        $cart->update([
            'order_type' => $request->order_type,
            'delivery_charge' => $deliveryCharge,
        ]);

        $this->updateCartTotals($cart);

        return $this->successresponse([
            'status' => 200,
            'message' => 'Order type updated successfully',

            'cart' => $cart->fresh([
                'items.menuItem',
                'items.variation',
                'coupon'
            ])
        ]);
    }

    public function remove($id)
    {
        $cartItem = CartItem::find($id);

        if (!$cartItem) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Cart item not found'
            ]);
        }

        $cart = Cart::find($cartItem->cart_id);

        $cartItem->delete();

        if ($cart) {

            $this->updateCartTotals($cart);
        }

        return $this->successresponse([
            'status' => 200,
            'message' => 'Removed successfully'
        ]);
    }



    public function quantity(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'cart_item_id' => 'required|numeric',

            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $cartItem = CartItem::find($request->cart_item_id);

        if (!$cartItem) {

            return response()->json([
                'status' => false,
                'message' => 'Cart item not found'
            ]);
        }



        $cartItem->update([
            'quantity' => $request->quantity,
            'total_price' => $cartItem->price * $request->quantity,
        ]);



        $cart = Cart::find($cartItem->cart_id);

        $this->updateCartTotals($cart);

        return response()->json([
            'status' => true,
            'price' => currencyFormat($cartItem->fresh()->total_price),
            'totalPrice' => currencyFormat($cart->fresh()->subtotal),
            'gst' => currencyFormat($cart->fresh()->gst_amount),
            'discount' => currencyFormat($cart->fresh()->discount),
            'total' => currencyFormat($cart->fresh()->total),
        ]);
    }



    public function applyCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'coupon' => 'required|string',
            'restaurantID' => 'nullable|numeric',

        ]);

        if ($validator->fails()) {

            return $this->successresponse([
                'status' => 422,
                'message' => $validator->errors()->first()
            ]);
        }

        $userId = auth()->id();

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Cart not found'
            ]);
        }

        $msg = '';

        $today = now();

        $coupon = Coupon::whereRaw('BINARY slug = ?', [$request->coupon])
            ->where('from_date', '<=', $today)
            ->where('to_date', '>=', $today)
            ->where('limit', '>', 0)
            ->where(function ($query) use ($request) {

                $query->where('restaurant_id', $request->restaurantID)
                    ->orWhere('restaurant_id', 0);
            })
            ->first();

        $totalUsed = 0;

        if (!blank($coupon)) {

            $totalUsed = Discount::where('coupon_id', $coupon->id)
                ->where('status', DiscountStatus::ACTIVE)
                ->count();
        }

        if (blank($coupon)) {

            $msg = 'This Coupon is Invalid';

        } elseif (
            $coupon->coupon_type == CouponType::VOUCHER
            && $coupon->restaurant_id != 0
            && $coupon->restaurant_id != $request->restaurantID
        ) {

            $msg = 'This Coupon is Invalid';

        } elseif ($totalUsed >= $coupon->limit) {

            $msg = 'This Coupon is Expired.';

        } else {

            $userUsed = Discount::where('coupon_id', $coupon->id)
                ->where('user_id', $userId)
                ->where('status', DiscountStatus::ACTIVE)
                ->exists();

            if ($userUsed) {

                $msg = 'You have already used this coupon.';
            }
        }


        if (!blank($msg)) {

            return $this->successresponse([
                'status' => 422,
                'message' => $msg,
                'coupon' => null
            ]);
        }

        $cart->update([
            'coupon_id' => $coupon->id
        ]);

        $this->updateCartTotals($cart);

        return $this->successresponse([
            'status' => 200,
            'message' => 'Coupon applied successfully',
            'coupon' => $coupon,
            'cart' => $cart->fresh([
                'items.menuItem',
                'items.variation',
                'coupon'
            ])
        ]);
    }


    private function updateCartTotals($cart)
    {
        $cart->loadMissing('coupon');

        $settings = Setting::pluck('value', 'key');

        $basicDeliveryCharge = (float) ($settings['basic_delivery_charge'] ?? 0);

        $chargePerKilo = (float) ($settings['charge_per_kilo'] ?? 0);

        $subtotal = CartItem::where('cart_id', $cart->id)
            ->sum('total_price');



        $discount = 0;

        if ($cart->coupon) {

            if ($cart->coupon->discount_type == 'percent') {

                $discount = ($subtotal * $cart->coupon->amount) / 100;

            } else {

                $discount = $cart->coupon->amount;
            }

            if ($discount > $subtotal) {

                $discount = $subtotal;
            }
        }



        $taxableAmount = $subtotal - $discount;

        if ($taxableAmount < 0) {

            $taxableAmount = 0;
        }



        $gstPercentage = 5;

        $gstAmount = ($taxableAmount * $gstPercentage) / 100;



        $deliveryCharge = 0;

        if ($cart->order_type == \App\Enums\OrderTypeStatus::DELIVERY) {

            $restaurant = Restaurant::find($cart->restaurant_id);

            if (
                $restaurant &&
                $restaurant->lat &&
                $restaurant->long &&
                $cart->latitude &&
                $cart->longitude
            ) {



                $distance = $this->calculateDistance(

                    $cart->latitude,
                    $cart->longitude,

                    $restaurant->lat,
                    $restaurant->long
                );



                $deliveryCharge = $basicDeliveryCharge
                    + ($distance * $chargePerKilo);

            } else {



                $deliveryCharge = $basicDeliveryCharge;
            }
        }



        $total = $taxableAmount
            + $gstAmount
            + $deliveryCharge;

        if ($total < 0) {

            $total = 0;
        }



        $cart->update([
            'subtotal' => $subtotal,
            'discount' => $discount,
            'gst_amount' => $gstAmount,
            'delivery_charge' => round($deliveryCharge, 2),
            'total' => round($total, 2),
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);

        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +

            cos(deg2rad($lat1)) *
            cos(deg2rad($lat2)) *

            sin($dLon / 2) *
            sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }
    public function removeCoupon()
    {
        $userId = auth()->id();

        $cart = Cart::where('user_id', $userId)->first();

        if (!$cart) {

            return $this->successresponse([
                'status' => 404,
                'message' => 'Cart not found'
            ]);
        }

        if (!$cart->coupon_id) {

            return $this->successresponse([
                'status' => 422,
                'message' => 'No coupon applied'
            ]);
        }

        $cart->update([
            'coupon_id' => null,
            'discount' => 0,
        ]);

        $this->updateCartTotals($cart->fresh());

        return $this->successresponse([
            'status' => 200,
            'message' => 'Coupon removed successfully',

            'cart' => $cart->fresh([
                'items.menuItem',
                'items.variation',
                'coupon'
            ])
        ]);
    }
}