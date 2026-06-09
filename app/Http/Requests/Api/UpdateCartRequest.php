<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'address_id' => 'nullable|numeric|exists:addresses,id',
            'order_type' => 'nullable|numeric',
            'remove_coupon' => 'nullable|boolean',
            'order_instructions' => 'nullable|string|max:500',
            'tip_amount' => 'nullable|numeric|min:0',
        ];
    }
}