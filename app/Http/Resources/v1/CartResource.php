<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $rawFees = [
            'packing_charge' => (float) $this->packing_charge,
            'platform_fee' => (float) $this->platform_fee,
            'surge_fee' => (float) $this->surge_fee,
            'large_order_fee' => (float) $this->large_order_fee,
            'tip_amount' => (float) $this->tip_amount,
        ];


        $formattedFees = [];
        foreach ($rawFees as $key => $amount) {
            if ($amount > 0) {
                $formattedFees[] = [
                    'key' => $key,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                    'amount' => $amount
                ];
            }
        }

        return [
            'cart_id' => $this->id,
            'restaurant_id' => $this->restaurant_id,
            'address_id' => $this->address_id,
            'order_type' => (int) $this->order_type,
            'order_instructions' => $this->order_instructions,

            'bill_details' => [
                'subtotal' => (float) $this->subtotal,
                'discount' => (float) $this->discount,
                'gst_amount' => (float) $this->gst_amount,
                'delivery_charge' => (float) $this->delivery_charge,
                'total_payable' => (float) $this->total,


                'fees' => $formattedFees,
            ],

            'sync_messages' => $this->sync_messages ?? [],

            'applied_coupon' => $this->whenLoaded('coupon', function () {
                return [
                    'code' => $this->coupon->slug,
                    'discount_type' => $this->coupon->discount_type,
                    'amount' => (float) $this->coupon->amount,
                ];
            }),

            'items' => CartItemResource::collection($this->whenLoaded('items')),
        ];
    }
}