<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'menu_id' => 'required|numeric|exists:menu_items,id',
            'variation_id' => 'nullable|numeric|exists:menu_item_variations,id',
            'address_id' => 'nullable|numeric|exists:addresses,id',
            'options' => 'nullable|array',
            'options.*' => 'nullable|numeric|exists:menu_item_options,id',
            'instructions' => 'nullable|string|max:500',
            'quantity' => 'nullable|integer|min:0',
            'order_instructions' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [

            'menu_id.required' => 'Menu item is required',
            'menu_id.exists' => 'Menu item not found',
            'variation_id.exists' => 'Variation not found',
            'address_id.required' => 'Address is required',
            'address_id.exists' => 'Address not found',
            'quantity.min' => 'Quantity must be at least 1',
        ];
    }
}