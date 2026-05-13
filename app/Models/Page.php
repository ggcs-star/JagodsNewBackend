<?php

namespace App\Models;

use App\Enums\Status;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends BaseModel
{
    use HasSlug;

    protected $table       = 'pages';
    protected $auditColumn       = true;
    protected $fillable    = ['title', 'slug', 'description', 'footer_menu_section_id'];

    public function getRouteKeyName()
    {
        return request()->segment(1) === 'admin' ? 'id' : 'slug';
    }


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function footer_menu_section()
    {
        return $this->belongsTo(FooterMenuSection::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->status == Status::ACTIVE ? '<span class="db-table-badge text-green-600 bg-green-100">' . trans('statuses.' . $this->status) . '</span>' : '<span class="db-table-badge text-red-600 bg-red-100">' . trans('statuses.' . $this->status) . '</span>';
    }
}
