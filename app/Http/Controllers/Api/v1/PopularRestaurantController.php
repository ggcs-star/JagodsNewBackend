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
        $current_time = now()->format('H:i');

        $query = Restaurant::leftJoin('orders', 'restaurant_id', '=', 'restaurants.id')
            ->select(
                'restaurants.id',
                'restaurants.name',
                'restaurants.slug',
                'restaurants.description',
                'restaurants.address',
                'restaurants.lat',
                'restaurants.long',
                'restaurants.coverImg',
                'restaurants.opening_time',
                'restaurants.closing_time',
                'restaurants.restroType',
                'restaurants.sort_order' 
            )
            ->selectRaw('count(orders.id) as orders_count')

            ->selectRaw("
            CASE
                WHEN opening_time > closing_time AND opening_time < ?
                    THEN 1
                WHEN opening_time < closing_time AND opening_time < ? AND closing_time > ?
                    THEN 1
                ELSE 0
            END as is_open
        ", [$current_time, $current_time, $current_time])

            ->groupBy(
                'restaurants.id',
                'restaurants.name',
                'restaurants.slug',
                'restaurants.description',
                'restaurants.address',
                'restaurants.lat',
                'restaurants.long',
                'restaurants.coverImg',
                'restaurants.opening_time',
                'restaurants.closing_time',
                'restaurants.restroType',
                'restaurants.sort_order'
            )

            ->where('restaurants.status', RestaurantStatus::ACTIVE)
            ->where('restaurants.current_status', CurrentStatus::YES)
            ->where('restaurants.id', '!=',28);


        if ($request->get('restaurants') === 'all') {

            $query->orderByRaw("
            CASE 
                WHEN restaurants.sort_order = 0 THEN 999 
                ELSE restaurants.sort_order 
            END ASC
        ");

            $query->orderBy('orders_count', 'desc');

        } else {

            $query->orderBy('orders_count', 'desc');
        }

        $bestSellingRestaurants = $query->get();

        try {
            return $this->successResponse([
                'status' => 200,
                'data' => PopularRestaurantResource::collection($bestSellingRestaurants)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
        }
    }
}
