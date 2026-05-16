<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\Page;

class PrivacyController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['site_title'] = 'Privacy Policy';
    }

    public function __invoke()
    {
        $page = Page::where('slug', 'privacy')->first();

        return view('frontend.page.privacy', array_merge($this->data, [
            'page' => $page
        ]));
    }
}
