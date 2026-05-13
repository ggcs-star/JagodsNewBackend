<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Enums\CouponType;
use App\Enums\DiscountStatus;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public array $coupons = [
        [
            'name'                 => "Off30%",
            'slug'                 => "off30",
            'discount_type'        => DiscountStatus::ACTIVE,
            'coupon_type'          => CouponType::COUPON,
            'restaurant_id'        => "1",
            'limit'                => "200",
            'user_limit'           => "100",
            'amount'               => "30.00",
            'minimum_order_amount' => "10.00",
        ],
        [
            'name'                 => "Off40%",
            'slug'                 => "off40",
            'discount_type'        => DiscountStatus::CANCELED,
            'coupon_type'          => CouponType::VOUCHER,
            'restaurant_id'        => "2",
            'limit'                => "200",
            'user_limit'           => "10",
            'amount'               => "40.00",
            'minimum_order_amount' => "10.00",
        ],
        [
            'name'                 => "Off40%",
            'slug'                 => "off40-1",
            'discount_type'        => DiscountStatus::CANCELED,
            'coupon_type'          => CouponType::VOUCHER,
            'restaurant_id'        => "3",
            'limit'                => "20",
            'user_limit'           => "10",
            'amount'               => "20.00",
            'minimum_order_amount' => "50.00",
        ],
        [
            'name'                 => "Off30%",
            'slug'                 => "off30-1",
            'discount_type'        => DiscountStatus::ACTIVE,
            'coupon_type'          => CouponType::COUPON,
            'restaurant_id'        => "4",
            'limit'                => "20",
            'user_limit'           => "1",
            'amount'               => "10.00",
            'minimum_order_amount' => "40.00",
        ],
        [
            'name'                 => "Off40%",
            'slug'                 => "off40-2",
            'discount_type'        => DiscountStatus::ACTIVE,
            'coupon_type'          => CouponType::COUPON,
            'restaurant_id'        => "5",
            'limit'                => "150",
            'user_limit'           => "10",
            'amount'               => "30.00",
            'minimum_order_amount' => "10.00",
        ],
        [
            'name'                 => "Off40%",
            'slug'                 => "off40-3",
            'discount_type'        => DiscountStatus::ACTIVE,
            'coupon_type'          => CouponType::COUPON,
            'restaurant_id'        => "6",
            'limit'                => "200",
            'user_limit'           => "100",
            'amount'               => "30.00",
            'minimum_order_amount' => "10.00",
        ],
        [
            'name'                 => "off10",
            'slug'                 => "off10",
            'discount_type'        => DiscountStatus::ACTIVE,
            'coupon_type'          => CouponType::COUPON,
            'restaurant_id'        => "0",
            'limit'                => "100",
            'user_limit'           => "5",
            'amount'               => "10.00",
            'minimum_order_amount' => "25.00",
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        if (env('DEMO_MODE')) {
            foreach ($this->coupons as $coupon) {
                Coupon::create([
                    'name'                 => $coupon['name'],
                    'slug'                 => $coupon['slug'],
                    'discount_type'        => $coupon['discount_type'],
                    'coupon_type'          => $coupon['coupon_type'],
                    'restaurant_id'        => $coupon['restaurant_id'],
                    'limit'                => $coupon['limit'],
                    'user_limit'           => $coupon['user_limit'],
                    'amount'               => $coupon['amount'],
                    'minimum_order_amount' => $coupon['minimum_order_amount'],
                    'from_date'            => Carbon::now()->subDays(5),
                    'to_date'              => Carbon::now()->addDay(365),
                    'creator_type'         => "App\Models\User",
                    'creator_id'           => "1",
                    'editor_type'          => "App\Models\User",
                    'editor_id'            => "1",
                ]);
            }
        }
    }
}
