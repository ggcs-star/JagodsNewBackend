<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\BannerStatus;
use App\Http\Controllers\BackendController;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\v1\BannerResource;
use App\Traits\ApiResponse;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends BackendController
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth:api');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function index()
    // {
    //     $banners= Banner::where('status',BannerStatus::ACTIVE)->orderBy('sort', 'asc')->get();
    //     return $this->successresponse(['success'=>200, 'data'=> BannerResource::collection($banners)]);
    // }
    
    public function index()
    {
        $banners = Banner::where('status', BannerStatus::ACTIVE)->orderBy('sort', 'asc')->get();
        $imageUrl = "https://images.jagods.com/banner/banner.svg";

        return $this->successresponse([
            'success' => 200,
            'data' => [
                'image_url' => $imageUrl,
                'banners' => BannerResource::collection($banners),
            ],
        ]);
    }
    
    
    
     public function getHomeAds()
    {
    $ads = [
        [
            'id' => 1,
            'title' => 'Summer Sale Banner',
            'subtitle' => 'Unlock exclusive food vouchers on Cloud Food Court and use them while ordering on Jagods',
            'media_type' => 'banner',
            'cta' => [
                'label' => 'Install & Save Now',
                'action_url' => 'https://your-app-link.com/install',
                'cta_color' => '#FF5C1B'
            ],
            'offers' => [
                [
                    'icon' => 'discount',
                    'title' => 'Flat ₹50–₹200 OFF',
                    'description' => 'on your first food order'
                ],
                [
                    'icon' => 'combo',
                    'title' => 'Daily Combo Offers',
                    'description' => 'on top restaurants'
                ],
                [
                    'icon' => 'store',
                    'title' => 'Use Online & In-Store',
                    'description' => 'at selected food courts'
                ]
            ],
            'media_url' => 'https://video-previews.elements.envatousercontent.com/ebf1f81d-9491-404e-a1ef-13fded979afd/watermarked_preview/watermarked_preview.mp4',
            'theme_color' => '#002F87',
            'position' => 'top_banner',
            'priority' => 1,
        ],
        [
            'id' => 2,
            'title' => 'App Promo Video',
            'subtitle' => null,
            'media_type' => 'video',
            'cta' => [],
            'offers' => [],
            'media_url' => 'https://reels.jagods.com/assets/Comp3.mp4',
            'redirect_url' => 'https://example.com/app',
            'position' => 'corner_card',
            'theme_color' => null,
            'priority' => 2,
        ],
    ];

    return response()->json([
        'success' => 200,
        'data' => [
            'ads' => $ads
        ],
    ]);
    }
}
