<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'menu_item_id',
        'variation_id',
        'options',
        'instructions',
        'quantity',
        'price',
        'total_price',
    ];

    protected $casts = [
        'options' => 'array',
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
        return $this->belongsTo(MenuItemVariation::class, 'variation_id');
    }
}