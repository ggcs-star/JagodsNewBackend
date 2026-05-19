<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'menu_item_id' => $this->menu_item_id,
            'name' => $this->menu_name,
            'slug' => $this->menu_slug,
            'image' => $this->menu_image,
            'quantity' => (int) $this->quantity,
            'unit_price' => (float) $this->unit_price,
            'price' => (float) $this->price,
            'total_price' => (float) $this->total_price,
            'is_available' => (bool) $this->is_available,
            'is_price_changed' => (bool) $this->is_price_changed,
            'variation_name' => $this->variation_name,
            'options' => $this->options ?? [],
            'instructions' => $this->instructions,
        ];
    }
}