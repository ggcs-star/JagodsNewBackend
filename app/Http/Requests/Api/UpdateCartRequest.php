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
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'order_type' => 'nullable|numeric',
            'remove_coupon' => 'nullable|boolean', // Naya field add kiya
        ];
    }
}