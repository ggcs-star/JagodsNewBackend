<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_type',
        'latitude',
        'longitude',
        'restaurant_id',
        'subtotal',
        'discount',
        'gst_amount',
        'delivery_charge',
        'total',
    ];



    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }
}