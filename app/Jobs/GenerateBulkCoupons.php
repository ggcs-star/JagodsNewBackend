<?php

namespace App\Jobs;

use App\Models\Coupon;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class GenerateBulkCoupons implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

 public function handle()
{
    $insertData = [];
    $generatedSlugs = [];

    for ($i = 0; $i < $this->data['quantity']; $i++) {

        do {
            if (!empty($this->data['prefix'])) {
    $prefix = strtoupper($this->data['prefix']);
    $slug = $prefix . strtoupper(Str::random(5));
} else {
    $slug = strtoupper(Str::random(10));
}
        } 
        while (
            in_array($slug, $generatedSlugs) ||
            Coupon::where('slug', $slug)->exists()
        );

        $generatedSlugs[] = $slug;

        $insertData[] = [
            'name' => $slug,
            'slug' => $slug,
            'discount_type' => $this->data['discount_type'],
            'coupon_type' => ($this->data['restaurant_id'] == 0) ? 5 : 10,
            'restaurant_id' => $this->data['restaurant_id'],
            'limit' => 1,
            'user_limit' => 1,
            'amount' => $this->data['amount'],
            'minimum_order_amount' => 0,
            'from_date' => $this->data['from_date'],
            'to_date' => $this->data['to_date'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (count($insertData) == 500) {
            Coupon::insert($insertData);
            $insertData = [];
        }
    }

    if (!empty($insertData)) {
        Coupon::insert($insertData);
    }
}
}