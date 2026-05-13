<?php

namespace App\Http\Resources\v1;

use App\Models\ThemeSettings;
use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request) : array
    {
        return [
            "site_logo"        => $this->themeImage('site_logo')->logo,
            "fav_icon"         => $this->themeImage('fav_icon')->faviconLogo,
            "site_footer_logo" => $this->themeImage('site_footer_logo')->footerLogo,
        ];
    }

    public function themeImage($key)
    {
        return ThemeSettings::where(['key' => $key])->first();
    }
}
