<?php

namespace App\Models;

use DB;
use Carbon\Carbon;
use App\Enums\CurrentStatus;
use App\Enums\DeliveryStatus;
use App\Enums\PickupStatus;
use App\Models\User;
use App\Enums\Status;
use App\Enums\TableStatus;
use App\Models\QrCode;
use App\Enums\WaiterStatus;
use Spatie\Sluggable\HasSlug;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\RatingsService;
use Illuminate\Support\Facades\Schema;
use Shipu\Watchable\Traits\WatchableTrait;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravel\Scout\Searchable;
class Restaurant extends BaseModel implements HasMedia
{
    use WatchableTrait, InteractsWithMedia, HasSlug, SoftDeletes, Searchable;
    protected $table = 'restaurants';
    protected $guarded = ['id'];
    protected $auditColumn = true;
    protected $dates = ['deleted_at'];
    protected $fakeColumns = [];

    protected $casts = [
        'status' => 'int',
        'current_status' => 'int',
        'user_id' => 'int',
        'delivery_status' => 'int',
        'pickup_status' => 'int',
        'delivery_charge' => 'int',
        'table_status' => 'int',
        'applied' => 'int',
        'creator_id' => 'int',
        'editor_id ' => 'int',
        //'coverImg' => '',
    ];

    public function getRouteKeyName()
    {
        return request()->segment(1) === 'admin' ? 'id' : 'slug';
    }

    public function avgRating($restaurantID)
    {
        $rating = new RatingsService();
        return $rating->avgRating($restaurantID);
    }

    public function creator()
    {
        return $this->morphTo();
    }

    public function editor()
    {
        return $this->morphTo();
    }

    public function cuisines()
    {
        return $this->belongsToMany(Cuisine::class, 'restaurant_cuisines');
    }

    public function coupons()
    {
        if (Schema::hasColumn('coupons', 'slug')) {
            return $this->hasMany(Coupon::class);
        }
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->with('media', 'roles');
    }


    public function getImageAttribute()
    {
        if (!empty($this->getFirstMediaUrl('restaurant'))) {
            $image = $this->getMedia('restaurant')->last();
            return $image->getUrl('image');
        }
        return asset('frontend/images/default/restaurant.png');
    }
    public function getLogoAttribute()
    {
        if (!empty($this->getFirstMediaUrl('restaurant_logo'))) {
            return asset($this->getFirstMediaUrl('restaurant_logo'));
        }
        return asset('frontend/images/default/logo.png');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('image')->performOnCollections('restaurant')->keepOriginalImageFormat();
    }

    public function getavgRatingsAttribute()
    {
        $rating = new RatingsService();
        $ratingArray = $rating->avgRating($this->id);
        if (!blank($ratingArray)) {
            return $ratingArray;
        }
        return null;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeRestaurantowner($query)
    {
        if (Auth::check() && auth()->user()->myrole != 1) {
            $query->where('user_id', auth()->id());
        }
    }

    public function OnModelCreated()
    {
        $qrCode = new QrCode();
        $qrCode->restaurant_id = $this->id;
        $qrCode->creator_type = $this->creator_type;
        $qrCode->creator_id = $this->creator_id;
        $qrCode->editor_type = $this->editor_type;
        $qrCode->editor_id = $this->editor_id;
        $qrCode->save();
    }

    public function qrCode()
    {
        return $this->hasOne(QrCode::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function ratings()
    {
        return $this->hasMany(RestaurantRating::class);
    }
    public function deleteMedia($restaurant, $mediaName, $mediaId)
    {
        $media = Media::where([
            'file_name' => $mediaName,
            'collection_name' => 'restaurant',
            'model_id' => $mediaId,
            'model_type' => Restaurant::class,
        ])->first();

        if ($media) {
            $media->delete();
        }
    }
    public function getCurrentStatusNameAttribute()
    {
        if ($this->current_status == CurrentStatus::YES) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('current_statuses.' . CurrentStatus::YES) . '</span>';
        } else if ($this->current_status == CurrentStatus::NO) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('current_statuses.' . CurrentStatus::NO) . '</span>';
        }
    }
    public function getWaiterStatusNameAttribute()
    {
        if ($this->waiter_status == WaiterStatus::ACTIVE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('statuses.' . WaiterStatus::ACTIVE) . '</span>';
        } else if ($this->waiter_status == WaiterStatus::INACTIVE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('statuses.' . WaiterStatus::INACTIVE) . '</span>';
        }
    }
    public function getDeliveryStatusNameAttribute()
    {
        if ($this->delivery_status == DeliveryStatus::ENABLE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('delivery_statuses.' . DeliveryStatus::ENABLE) . '</span>';
        } else if ($this->delivery_status == DeliveryStatus::DISABLE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('delivery_statuses.' . DeliveryStatus::DISABLE) . '</span>';
        }
    }
    public function getPicupStatusNameAttribute()
    {
        if ($this->pickup_status == PickupStatus::ENABLE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('pickup_statuses.' . PickupStatus::ENABLE) . '</span>';
        } else if ($this->pickup_status == PickupStatus::DISABLE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('pickup_statuses.' . PickupStatus::DISABLE) . '</span>';
        }
    }
    public function getTableStatusNameAttribute()
    {
        if ($this->table_status == TableStatus::ENABLE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('table_statuses.' . TableStatus::ENABLE) . '</span>';
        } else if ($this->table_status == TableStatus::DISABLE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('table_statuses.' . TableStatus::DISABLE) . '</span>';
        }
    }
    public function getStatusNameAttribute()
    {
        if ($this->status == Status::ACTIVE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('statuses.' . Status::ACTIVE) . '</span>';
        } else if ($this->status == Status::INACTIVE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('statuses.' . Status::INACTIVE) . '</span>';
        }
    }


    public function getIsOpenAttribute()
    {
        $now = now()->format('H:i:s');

        if ($this->opening_time > $this->closing_time) {
            return $now >= $this->opening_time || $now <= $this->closing_time;
        }

        return $now >= $this->opening_time && $now <= $this->closing_time;
    }
    public function banners()
    {
        return $this->hasMany(RestaurantBanner::class)
            ->where('status', 1)
            ->orderByRaw('sort_order = 0, sort_order ASC');
    }

    protected function makeAllSearchableUsing($query)
    {
        return $query->with('menuItems');
    }

    public function toSearchableArray()
    {
        $array = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => strip_tags($this->description ?? ''),
            'address' => $this->address,
            'status' => (int) $this->status,
            'current_status' => (int) $this->current_status,
            'restroType' => $this->restroType,
            'total_orders' => (int) $this->total_orders,
            'is_open' => (bool) $this->is_open,
            'avg_rating' => (float) $this->avg_rating,
            'total_reviews' => (int) $this->total_reviews,
            '_geo' => [
                'lat' => (float) $this->lat,
                'lng' => (float) $this->long,
            ],
        ];

        if ($this->relationLoaded('menuItems') || $this->exists) {
            $array['menu_item_names'] = $this->menuItems
                ->where('status', \App\Enums\MenuItemStatus::ACTIVE)
                ->pluck('name')
                ->implode(', ');
        } else {
            $array['menu_item_names'] = '';
        }

        return $array;
    }


    public function shouldBeSearchable()
    {
        return $this->status == Status::ACTIVE && $this->current_status == CurrentStatus::YES;
    }
}
