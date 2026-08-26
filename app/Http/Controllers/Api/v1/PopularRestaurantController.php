<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CurrentStatus;
use App\Enums\RestaurantStatus;
use App\Http\Resources\v1\PopularRestaurantResource;
use App\Models\Restaurant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Illuminate\Support\Facades\Log;
use Throwable;
use Illuminate\Support\Facades\Cache;
class PopularRestaurantController extends BackendController
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
        // $this->middleware('auth:api');

    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function index(Request $request)
    {
        try {

            $filterType = $request->get('restaurants') === 'all' ? 'all' : 'popular';
            $cacheKey = "home_restaurants_{$filterType}";

            $ttl = now()->addMinutes(5);

            $cachedData = Cache::remember($cacheKey, $ttl, function () use ($filterType) {

                $query = Restaurant::select([
                    'id',
                    'name',
                    'slug',
                    'coverImg',
                    'opening_time',
                    'closing_time',
                    'restroType',
                    'sort_order',
                    'total_orders',
                    'description',
                    'address',
                    'avg_rating',
                    'total_reviews',
                ])
                    ->where('status', RestaurantStatus::ACTIVE)
                    ->where('current_status', CurrentStatus::YES)
                    ->where('id', '!=', 28);

                if ($filterType === 'all') {
                    $query->orderByRaw("
                    CASE
                        WHEN sort_order = 0 THEN 999
                        ELSE sort_order
                    END ASC
                ")->orderByDesc('total_orders');
                } else {
                    $query->orderByDesc('total_orders');
                }

                return PopularRestaurantResource::collection(
                    $query->get()
                )->resolve();
            });

            return $this->successResponse(
                message: 'Restaurants fetched successfully.',
                data: $cachedData
            );

        } catch (Throwable $e) {

            Log::error('PopularRestaurant API Error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return $this->serverErrorResponse(
                message: config('app.debug')
                ? $e->getMessage()
                : 'Something went wrong while fetching restaurants.'
            );
        }
    }
}
