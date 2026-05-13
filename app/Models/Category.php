<?php

namespace App\Models;

use App\Enums\CategoryRequested;
use App\Enums\CategoryStatus;
use App\Models\BaseModel;
use Shipu\Watchable\Traits\WatchableTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends BaseModel implements HasMedia
{
    use HasSlug, WatchableTrait, InteractsWithMedia;

    protected $table       = 'categories';
    protected $auditColumn       = true;
    protected $fillable    = ['name', 'slug', 'description', 'status', 'requested'];
    protected $casts = [
        'status' => 'int',
        'requested' => 'int',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getImageAttribute()
    {
        if (!empty($this->getFirstMediaUrl('categories'))) {
            return asset($this->getFirstMediaUrl('categories'));
        }

        return asset('frontend/images/default/category.png');

    }

    public function creator()
    {
        return $this->morphTo();
    }

    public function OnModelCreating()
    {
        $roleID          = auth()->user()->myrole ?? 0;
        $this->requested = CategoryRequested::NON_REQUESTED;
        if ($roleID > 1) {
            $this->requested = CategoryRequested::REQUESTED;
            $this->status    = CategoryStatus::INACTIVE;
        }
    }

    public function OnModelSaving()
    {
        $roleID = auth()->user()->myrole ?? 0;
        if ($roleID == 1) {
            $this->status = request('status');
        }
    }

    public function getActionButtonAttribute()
    {
        $roleID = auth()->user()->myrole ?? 0;
        if ($roleID > 1) {
            if (($this->creator_id == auth()->id()) && ($this->status == CategoryStatus::INACTIVE)) {
                return true;
            }
            return false;
        }
        return true;
    }

    public function scopeOwner($query)
    {
        $roleID = auth()->user()->myrole ?? 0;
        if ($roleID > 1) {
            $query->where('creator_id', auth()->id());
            $query->where('status', CategoryStatus::INACTIVE);
        }
    }

    public function MenuItems()
    {
        return $this->belongsToMany(MenuItem::class, 'category_menu_items');
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == CategoryStatus::ACTIVE) {
            return '<span class="db-table-badge text-green-600 bg-green-100">' . trans('statuses.' . CategoryStatus::ACTIVE) . '</span>';
        } else if ($this->status == CategoryStatus::INACTIVE) {
            return '<span class="db-table-badge text-red-600 bg-red-100">' . trans('statuses.' . CategoryStatus::INACTIVE) . '</span>';
        }
    }
}
