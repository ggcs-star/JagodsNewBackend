<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantBanner extends Model
{
    use HasFactory;

    protected $table = 'restaurant_banners';

    protected $fillable = [
        'restaurant_id',
        'title',
        'description',
        'banner_image',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

  

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

  

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order', 'asc');
    }

    
    public function getBannerImageUrlAttribute()
    {
        return asset($this->banner_image);
    }
}