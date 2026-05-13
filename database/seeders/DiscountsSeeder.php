<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Discount;
use App\Enums\Status;

class DiscountsSeeder extends Seeder
{
    public array $discountOptions = [
        [
            "order_id"  => 9,
            "coupon_id" => 7,
            "user_id"   => '2',
            "amount"    => "10.00",
            "status"    => 5,
        ],
    ];

    /**
     * Run the database seeds.
     */

    public function run(){
        if (env('DEMO_MODE')) {
            foreach ($this->discountOptions as $discountOption) {
                Discount::create([
                    'order_id'  => $discountOption['order_id'],
                    'coupon_id' => $discountOption['coupon_id'],
                    'user_id'   => $discountOption['user_id'],
                    'amount'    => $discountOption['amount'],
                    'status'    => $discountOption['status'],
                ]);
            }
        }
    }
}
