<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ThemeSettings extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = "settings";
    public $timestamps = false;

    public function getLogoAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('site_logo'))) {
            return asset($this->getFirstMediaUrl('site_logo'));
        }
        return asset('images/seeder/settings/logo.png');
    }

    public function getFaviconAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('fav_icon'))) {
            return asset($this->getFirstMediaUrl('fav_icon'));
        }
        return asset('images/seeder/settings/favicon.png');
    }

    public function getFooterLogoAttribute() : string
    {
        if (!empty($this->getFirstMediaUrl('site_footer_logo'))) {
            return asset($this->getFirstMediaUrl('site_footer_logo'));
        }
        return asset('images/seeder/settings/footer-logo.png');
    }
}
