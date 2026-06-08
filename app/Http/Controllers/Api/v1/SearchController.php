<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\CurrentStatus;
use App\Http\Resources\v1\PopularRestaurantResource;
use App\Models\TimeSlot;
use App\Enums\TableStatus;
use App\Models\Restaurant;
use App\Enums\PickupStatus;
use App\Enums\RatingStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\DeliveryStatus;
use App\Enums\RestaurantStatus;
use App\Models\RestaurantRating;
use App\Http\Services\RatingsService;
use App\Http\Services\RestaurantService;
use App\Http\Resources\v1\RatingResource;
use App\Http\Controllers\BackendController;
use App\Http\Resources\v1\MenuItemResource;
use App\Http\Resources\v1\RestaurantResource;
use App\Models\MenuItem;

class SearchController extends BackendController
{
    use ApiResponse;
    protected  $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Restaurants';
        // $this->middleware('auth:api');
        $this->restaurantService = $restaurantService;
    }
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    // public function index(Request $request)
    // {
        
       
    //     if (!blank($request->get('name'))) {
    //         $name = $request->get('name');
    //     }

    //     $expedition = null;
    //     if (!blank($request->get('expedition'))) {
    //         $expedition = $request->get('expedition');
    //     }

    //     try {
    //         $restaurants = $this->getallrestaurant($name, $expedition);
            
    //         return $this->successResponse(['status' => 200, 'data' => PopularRestaurantResource::collection($restaurants)]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'exception' => get_class($e),
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTrace(),
    //         ]);
    //     }
    // }



//     public function getallrestaurant($name, $expedition)
// {
//     $queryArray = [];
//     $queryArray['status'] = RestaurantStatus::ACTIVE;
//     $queryArray['current_status'] = CurrentStatus::YES;

//     if (!blank($expedition)) {
//         if ($expedition == 'delivery') {
//             $queryArray['delivery_status'] = DeliveryStatus::ENABLE;
//         } elseif ($expedition == 'pickup') {
//             $queryArray['pickup_status'] = PickupStatus::ENABLE;
//         } elseif ($expedition == 'table') {
//             $queryArray['table_status'] = TableStatus::ENABLE;
//         }
//     }

//     $current_time = now()->format('H:i');

//     $restaurants = Restaurant::where($queryArray)

//         ->where(function ($q) use ($current_time) {
//             $q->where(function ($q2) use ($current_time) {
//                 $q2->where('opening_time', '>', 'closing_time')
//                   ->where('opening_time', '<', $current_time);
//             })
//             ->orWhere(function ($q3) use ($current_time) {
//                 $q3->where('opening_time', '<', 'closing_time')
//                   ->where('opening_time', '<', $current_time)
//                   ->where('closing_time', '>', $current_time);
//             });
//         });

//     if (!blank($name)) {
//     $restaurants->where(function ($q) use ($name) {

        
//         $q->where('name', 'LIKE', '%' . $name . '%')

    
//         ->orWhereHas('menuItems', function ($q2) use ($name) {
//             $q2->where('name', 'LIKE', '%' . $name . '%');
//         });

//     });
// }

//     return $restaurants->descending()->get();
// }


public function index(Request $request)
{
    $name = $request->get('name');
    $expedition = $request->get('expedition');

    try {
        $restaurants = $this->getallrestaurant($name, $expedition);

        return $this->successResponse([
            'status' => 200,
            'data' => PopularRestaurantResource::collection($restaurants)
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ]);
    }
}


public function getallrestaurant($name, $expedition)
{
    $queryArray = [
        'status' => RestaurantStatus::ACTIVE,
        'current_status' => CurrentStatus::YES,
    ];

    if (!blank($expedition)) {
        if ($expedition == 'delivery') {
            $queryArray['delivery_status'] = DeliveryStatus::ENABLE;
        } elseif ($expedition == 'pickup') {
            $queryArray['pickup_status'] = PickupStatus::ENABLE;
        } elseif ($expedition == 'table') {
            $queryArray['table_status'] = TableStatus::ENABLE;
        }
    }

    $current_time = now()->format('H:i');

    $restaurants = Restaurant::where($queryArray)
        ->where(function ($q) use ($current_time) {
            $q->where(function ($q2) use ($current_time) {
                $q2->where('opening_time', '>', 'closing_time')
                   ->where('opening_time', '<', $current_time);
            })
            ->orWhere(function ($q3) use ($current_time) {
                $q3->where('opening_time', '<', 'closing_time')
                   ->where('opening_time', '<', $current_time)
                   ->where('closing_time', '>', $current_time);
            });
        });

   
if (!blank($name)) {

    $name = strtolower(trim($name));
    $short = substr($name, 0, 3);

    $restaurants->select('*')
        ->selectRaw("
            (
                CASE 
                    WHEN LOWER(name) LIKE ? THEN 100
                    WHEN LOWER(name) LIKE ? THEN 80
                    WHEN LOWER(name) LIKE ? THEN 60
                    WHEN SOUNDEX(name) = SOUNDEX(?) THEN 50
                    ELSE 10
                END
            ) as relevance
        ", [
            "%$name%",    
            "$name%",     
            "%$short%",    
            $name          
        ])

        
        ->where(function ($q) use ($name, $short) {
            $q->whereRaw("LOWER(name) LIKE ?", ["%$short%"])
              ->orWhereHas('menuItems', function ($q2) use ($short) {
                  $q2->whereRaw("LOWER(name) LIKE ?", ["%$short%"]);
              });
        })

        ->orderByDesc('relevance');

} else {
    $restaurants->limit(2);
}

    return $restaurants->latest()->get(); 
}


public function globalSearch(Request $request)
{
    try {
        $query = $request->input('query', ''); 
        $lat = $request->input('lat'); 
        $lng = $request->input('lng');
        $radius = 10000; 
        
        $restaurantSearch = Restaurant::search($query);

        if ($lat && $lng) {
            $restaurantSearch->options([
                'filter' => "_geoRadius({$lat}, {$lng}, {$radius})",
                'sort' => ["_geoPoint({$lat}, {$lng}):asc", "total_orders:desc"]
            ]);
        } else {
            $restaurantSearch->orderBy('total_orders', 'desc');
        }

        $restaurantSearch->query(function ($query) {
            $query->with(['media']); 
        });

        $restaurants = $restaurantSearch->take(15)->get();

        return response()->json([
            'status' => 200,
            'message' => 'Search completed successfully.',
            'data' => PopularRestaurantResource::collection($restaurants)
        ], 200);

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Meilisearch Global Search Error: ' . $e->getMessage());
        return response()->json([
            'status' => 500,
            'message' => 'Failed to execute search. Please try again later.',
            'error' => config('app.env') !== 'production' ? $e->getMessage() : null
        ], 500);
    }
}
}
