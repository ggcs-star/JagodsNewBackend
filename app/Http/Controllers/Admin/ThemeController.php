<?php

namespace App\Http\Controllers\Admin;

use Setting;
use Illuminate\Http\Request;
use App\Models\ThemeSettings;
use App\Http\Controllers\BackendController;

class ThemeController extends BackendController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:setting'])->only('update');
    }

    public function index()
    {
        return view('admin.setting.theme');
    }

    public function update(Request $request)
    {
        // if (env('DEMO_MODE')) {
        //     return back()->withError('The theme setting is disable for the demo');
        // }

        $themeSettingArray = $this->validate($request, $this->themeValidateArray());

        if ($request->hasFile('site_logo')) {
            $themeSetting = ThemeSettings::updateOrCreate(['key' => 'site_logo'], []);
            $themeSetting->clearMediaCollection('site_logo');
            $themeSetting->addMediaFromRequest('site_logo')->toMediaCollection('site_logo');
        } else {
            $themeSettingArray['site_logo'] = Setting::get('site_logo');
        }

        if ($request->hasFile('fav_icon')) {
            $themeSetting = ThemeSettings::updateOrCreate(['key' => 'fav_icon'], []);
            $themeSetting->clearMediaCollection('fav_icon');
            $themeSetting->addMediaFromRequest('fav_icon')->toMediaCollection('fav_icon');
        } else {
            $themeSettingArray['fav_icon'] = Setting::get('fav_icon');
        }

        if ($request->hasFile('site_footer_logo')) {
            $themeSetting = ThemeSettings::updateOrCreate(['key' => 'site_footer_logo'], []);
            $themeSetting->clearMediaCollection('site_footer_logo');
            $themeSetting->addMediaFromRequest('site_footer_logo')->toMediaCollection('site_footer_logo');
        } else {
            $themeSettingArray['fav_icon'] = Setting::get('site_footer_logo');
        }

        Setting::set($themeSettingArray);
        Setting::save();

        return redirect(route('admin.setting.theme'))->withSuccess('The Theme setting updated successfully');
    }

    private function themeValidateArray()
    {
        return [
            'site_logo'        => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3096',
            'fav_icon'         => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3096',
            'site_footer_logo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:3096',
        ];
    }
}
