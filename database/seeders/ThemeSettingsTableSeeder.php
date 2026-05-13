<?php

namespace Database\Seeders;

use App\Models\ThemeSettings;
use Illuminate\Database\Seeder;

class ThemeTableSeeder extends Seeder
{
    /**
     
Run the database seeds.*/
  public function run(): void{
    { 
        ThemeSettings::updateOrCreate(['key' => 'site_logo']);
        ThemeSettings::updateOrCreate(['key' => 'fav_icon']); 
        ThemeSettings::updateOrCreate(['key' => 'site_footer_logo']); 
    }
}
}
