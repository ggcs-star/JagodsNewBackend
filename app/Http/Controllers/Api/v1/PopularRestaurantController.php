<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CurrentStatus;
use App\Enums\RestaurantStatus;
use App\Http\Resources\v1\PopularRestaurantResource;
use App\Models\Restaurant;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;

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
    // public function index()
    // {
    //     // dd('test');
    //     $current_time = now()->format('H:i');

    //     $bestSellingRestaurants  = Restaurant::leftJoin('orders', 'restaurant_id', '=', 'restaurants.id')
    //         ->select(
    //             'restaurants.id',
    //             'restaurants.name',
    //             'restaurants.slug',
    //             'restaurants.description',
    //             'restaurants.address',
    //             'restaurants.lat',
    //             'restaurants.long',
    //             'restaurants.coverImg',
    //             'restaurants.opening_time',
    //             'restaurants.closing_time',
    //             'restaurants.restroType',
    //         )
    //         ->selectRaw('count(orders.id) as orders_count')

    //         ->selectRaw("
    //             CASE
    //                 WHEN opening_time > closing_time AND opening_time < '$current_time'
    //                     THEN 1
    //                 WHEN opening_time < closing_time AND opening_time < '$current_time' AND closing_time > '$current_time'
    //                     THEN 1
    //                 ELSE 0
    //             END as is_open
    //         ")

    //         ->groupBy(
    //             'restaurants.id',
    //             'restaurants.name',
    //             'restaurants.slug',
    //             'restaurants.description',
    //             'restaurants.address',
    //             'restaurants.lat',
    //             'restaurants.long',
    //             'restaurants.coverImg',
    //             'restaurants.opening_time',
    //             'restaurants.closing_time',
    //             'restaurants.restroType',
    //         )

    //         ->orderBy('orders_count', 'desc')

    //         ->where('restaurants.status', RestaurantStatus::ACTIVE)
    //         ->where('restaurants.current_status', CurrentStatus::YES)

    //         ->get();

    //     try {

    //         return $this->successResponse([
    //             'status'=> 200,
    //             'data' => PopularRestaurantResource::collection($bestSellingRestaurants)
    //         ]);

    //     } catch (\Exception $e){

    //         return response()->json([
    //             'exception' => get_class($e),
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTrace(),
    //         ]);
    //     }
    // }


    public function index(Request $request)
    {
        try {
            $filterType = $request->get('restaurants') === 'all' ? 'all' : 'popular';

            $cacheKey = "home_restaurants_{$filterType}";
            $ttl = now()->addMinutes(rand(4, 6));

            $bestSellingRestaurants = \Illuminate\Support\Facades\Cache::remember($cacheKey, $ttl, function () use ($filterType) {

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

                return $query->get();
            });

            return $this->successResponse([
                'status' => 200,
                'message' => 'Restaurants fetched successfully.',
                'data' => PopularRestaurantResource::collection($bestSellingRestaurants)
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Home Page Restaurant API Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong while fetching restaurants.',
                'error' => config('app.env') !== 'production' ? $e->getMessage() : null
            ], 500);
        }
    }
}
