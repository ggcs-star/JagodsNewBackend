<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Order;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\TimeSlot;
use App\Enums\OrderStatus;
use App\Models\Restaurant;
use App\Enums\RatingStatus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Enums\DiscountStatus;
use App\Enums\MenuItemStatus;
use App\Models\RestaurantRating;
use App\Http\Services\RatingsService;
use App\Http\Services\RestaurantService;
use App\Http\Resources\v1\CouponResource;
use App\Http\Resources\v1\RatingResource;
use App\Http\Controllers\BackendController;
use App\Http\Resources\v1\MenuItemResource;
use App\Http\Resources\v1\RestaurantResource;
use App\Models\MenuItem;
use App\Http\Resources\v1\RestaurantBannerResource;

class RestaurantController extends BackendController
{
    use ApiResponse;
    protected $restaurantService;

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
    public function index($id = null, $status = null, $applied = null)
    {
        try {
            $restaurants = $this->restaurantService->getallrestaurant($id, $status, $applied);
            return $this->successResponse(['status' => 200, 'data' => RestaurantResource::collection($restaurants)]);
        } catch (\Exception $e) {
            return response()->json([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $e->getTrace(),
            ]);
        }
    }


    // public function show($id)
    // {
    //     $this->data['restaurant'] = Restaurant::findOrFail($id);
    //     // dd($this->data['restaurant']);
    //     $rating                   = new RatingsService();
    //     $ratingArray              = $rating->avgRating($this->data['restaurant']->id);
    //     $RestaurantRatings        = RestaurantRating::where(['restaurant_id' => $this->data['restaurant']->id, 'status' => RatingStatus::ACTIVE])->get();
    //     $this->data['timeSlots']  = TimeSlot::where(['restaurant_id' => $this->data['restaurant']->id])->get();

    //     $this->data['restaurant'] = new RestaurantResource($this->data['restaurant']);
    //     $this->data['menuItems'] = MenuItemResource::collection(
    //     $this->data['restaurant']->menuItems()->where('status', 5)->get());
    //     $this->data['reviews']    = RatingResource::collection($RestaurantRatings);
    //     $this->data['countUser']  = $ratingArray['countUser'];
    //     $this->data['avgRating']  = $ratingArray['avgRating'];



    //     $this->data['vouchers'] = [];
    //     // $today = date('Y-m-d h:i:s');
    //     // $vouchers = Coupon::whereDate('to_date', '>', $today)
    //     //     ->where('restaurant_id', '=', $this->data['restaurant']->id)
    //     //     ->whereDate('from_date', '<', $today)
    //     //     ->where('limit', '>', 0)->get();
    //     // if (!blank($vouchers)) {
    //     //     $data = [];
    //     //     foreach ($vouchers as $voucher) {
    //     //         $total_used = Discount::where('coupon_id', $voucher->id)->where('status', \App\Enums\DiscountStatus::ACTIVE)->count();
    //     //         if ($total_used < $voucher->limit) {
    //     //             $data[] = $voucher;
    //     //         }
    //     //     }
    //     //     if (!blank($data)) {
    //     //         $this->data['vouchers']         = CouponResource::collection($data);
    //     //     }
    //     // }

    //     if (auth()->user()) {
    //         $order = Order::where([
    //             'restaurant_id' => $id,
    //             'status'        => OrderStatus::COMPLETED,
    //             'user_id'       => auth()->user()->id
    //         ])->get();
    //     } else {
    //         $order = [];
    //     }
    //     $this->data['order_status']        = !blank($order);

    //     try {
    //         return $this->successResponse(['status' => 200, 'data' => $this->data]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'exception' => get_class($e),
    //             'message' => $e->getMessage(),
    //             'trace' => $e->getTrace(),
    //         ]);
    //     }
    // }

    public function show($id)
    {
        $restaurant = Restaurant::with([
            'banners',
            'menuItems' => function ($query) {
                $query->where('status', MenuItemStatus::ACTIVE);
            }
        ])->findOrFail($id);

        $rating = new RatingsService();
        $ratingArray = $rating->avgRating($restaurant->id);

        $categoriesData = \App\Models\Category::whereHas('menuItems', function ($query) use ($id) {
            $query->where('restaurant_id', $id)
                ->where('status', MenuItemStatus::ACTIVE);
        })
            ->select('id', 'name')
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                ];
            })
            ->toArray();

        $restaurantRatings = RestaurantRating::where([
            'restaurant_id' => $restaurant->id,
            'status' => RatingStatus::ACTIVE
        ])->get();

        $timeSlots = TimeSlot::where('restaurant_id', $restaurant->id)->get();

        $this->data['restaurant'] = new RestaurantResource($restaurant);
        $this->data['banners'] = RestaurantBannerResource::collection($restaurant->banners);
        $this->data['categories'] = $categoriesData;
        $this->data['menuItems'] = MenuItemResource::collection($restaurant->menuItems);
        $this->data['reviews'] = RatingResource::collection($restaurantRatings);
        $this->data['timeSlots'] = $timeSlots;
        $this->data['countUser'] = $ratingArray['countUser'];
        $this->data['avgRating'] = $ratingArray['avgRating'];
        $this->data['vouchers'] = [];
        $this->data['order_status'] = true;

        try {
            return $this->successResponse(['status' => 200, 'data' => $this->data]);
        } catch (\Exception $e) {
            return response()->json([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTrace() : [],
            ], 500);
        }
    }

}
