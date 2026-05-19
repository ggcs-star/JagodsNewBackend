<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\FrontendController;
use App\Models\Page;

class TermController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();

        $this->data['site_title'] = 'Terms & Conditions';
    }

    public function __invoke()
    {
        $page = Page::where('slug', 'terms-and-condition')->first();

        return view('frontend.page.terms', array_merge($this->data, [
            'page' => $page
        ]));
    }
}
