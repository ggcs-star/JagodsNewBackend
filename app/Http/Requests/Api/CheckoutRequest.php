<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentMethod;
class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
       return [
          
            'payment_method' => 'required|in:' . PaymentMethod::CASH_ON_DELIVERY . ',' . PaymentMethod::RAZORPAY,
            
        ];
    }
}