<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'variation_id',
        'menu_name',
        'menu_slug',
        'menu_image',
        'variation_name',
        'unit_price',
        'discount_price',
        'price',
        'total_price',
        'options',
        'instructions',
        'quantity',
        'is_available',
        'is_price_changed',
        'price_changed_at',
        'notes',
    ];

    protected $casts = [

        'options' => 'array',
        'unit_price' => 'float',
        'discount_price' => 'float',
        'price' => 'float',
        'total_price' => 'float',
        'quantity' => 'integer',
        'is_available' => 'boolean',
        'is_price_changed' => 'boolean',
        'price_changed_at' => 'datetime',
    ];


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function variation()
    {
        return $this->belongsTo(
            MenuItemVariation::class,
            'variation_id'
        );
    }

    public function getFormattedPriceAttribute()
    {
        return currencyFormat($this->price);
    }

    public function getFormattedTotalAttribute()
    {
        return currencyFormat($this->total_price);
    }
}