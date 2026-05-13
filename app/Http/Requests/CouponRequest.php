<?php

namespace App\Http\Requests;

use App\Models\Coupon;
use App\Models\Discount;
use App\Enums\CouponType;
use App\Models\Restaurant;
use App\Enums\DiscountType;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'                 => ['required', 'string'],
            'slug'                 => ['nullable', 'string'],
            'discount_type'        => ['required', 'numeric'],
            'from_date'            => ['required', 'date'],
            'to_date'              => ['required', 'date', 'after:from_date'],
            'restaurant_id'        => ['nullable', 'numeric'],
            'limit'                => ['required', 'numeric'],
            'user_limit'           => ['required', 'numeric'],
            'amount'               => ['required', 'numeric'],
            'minimum_order_amount' => ['nullable', 'numeric'],
            'limit'                => ['required', 'numeric'],
        ];

        if ($this->route('coupon') == null) {
            $rules['from_date'][] = 'after_or_equal:today';
        } 

        return $rules;
    }

    // public function withValidator($validator)
    // {
    //     $validator->after(function ($validator) {
    //         if ($this->isPercentage() && request('amount') > 99) {
    //             $validator->errors()->add('amount', 'Percentage amount can\'t be greater than 99.');
    //         }

    //         if ($this->route('coupon') == null)
    //         {
    //             if (request('restaurant_id') != 0) {
    //                 if ($this->activeCoupon()) {
    //                     if (auth()->user()->restaurant_id != 0) {
    //                         $validator->errors()->add('name', 'This restaurant already has an active coupon .');
    //                     }
    //                     $validator->errors()->add('restaurant_id', 'This restaurant already has an active coupon .');
    //                 }
    //             }
    //         };
    //     });
    // }
    
    public function withValidator($validator)
{
    $validator->after(function ($validator) {

        // Percentage validation remains same
        if ($this->isPercentage() && request('amount') > 99) {
            $validator->errors()->add(
                'amount',
                'Percentage amount can\'t be greater than 99.'
            );
        }

        
    });
}


    public function isPercentage()
    {
        return request('discount_type') == DiscountType::PERCENTAGE ? true : false;
    }

    public function activeCoupon()
    {

        $today = date('Y/m/d h:i');


        $coupons = Coupon::where('restaurant_id', request('restaurant_id'))
            ->whereDate('to_date', '>=', $today)
            ->whereDate('from_date', '<=', $today)
            ->where('limit', '>', 0)
            ->get();

        if (!blank($coupons)) {
            foreach ($coupons as $coupon) {
                if (!($coupon->id == $this->coupon)) {
                    $total_used = Discount::where('coupon_id', $coupon->id)->where('status', \App\Enums\DiscountStatus::ACTIVE)->count();
                    if ($total_used < $coupon->limit) {
                        return true;
                    }
                }
            }
            return false;
        }
        return false;
    }
}