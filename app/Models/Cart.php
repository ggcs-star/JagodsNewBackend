<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [

        'user_id',
        'restaurant_id',
        'address_id',
        'coupon_id',
        'order_type',
        'order_instructions',
        'subtotal',
        'discount',
        'gst_amount',
        'delivery_charge',
        'packing_charge',
        'handling_charge',
        'platform_fee',
        'large_order_fee',
        'surge_fee',
        'tip_amount',
        'total',
        'status',
        'is_price_changed',
        'is_expired',
        'notes',
        'expires_at',
        'sync_messages',
    ];

    protected $casts = [

        'subtotal' => 'float',
        'discount' => 'float',
        'gst_amount' => 'float',
        'delivery_charge' => 'float',
        'packing_charge' => 'float',
        'handling_charge' => 'float',
        'large_order_fee' => 'float',
        'platform_fee' => 'float',
        'surge_fee' => 'float',
        'tip_amount' => 'float',
        'total' => 'float',
        'is_price_changed' => 'boolean',
        'is_expired' => 'boolean',
        'expires_at' => 'datetime',
         'sync_messages' => 'array',
    ];

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}