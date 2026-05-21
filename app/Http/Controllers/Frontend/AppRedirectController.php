<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\FrontendController;

class AppRedirectController extends FrontendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Open App";
    }

    public function handleRedirect(Request $request)
    {

        $userAgent = $request->header('User-Agent');

        if (preg_match('/iPhone|iPad|iPod/i', $userAgent)) {
            $device = 'ios';
        } elseif (preg_match('/Android/i', $userAgent)) {
            $device = 'android';
        } else {
            $device = 'desktop';
        }

        $this->data['device'] = $device;

        return view('frontend.app-redirect', $this->data);
    }
}