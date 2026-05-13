<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\BannerStatus;
use App\Enums\CouponType;
use App\Enums\DiscountStatus;
use App\Enums\DiscountType;
use App\Http\Controllers\BackendController;
use App\Http\Requests\BannerRequest;
use App\Http\Resources\v1\BannerResource;
use App\Models\Coupon;
use App\Models\Discount;
use App\Traits\ApiResponse;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Jobs\GenerateBulkCoupons;
class CouponController extends BackendController
{
    use ApiResponse;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
  public function apply(Request $request)
{
    $msg = '';
    $today = date('Y-m-d h:i:s');

    $coupon = Coupon::whereRaw('BINARY slug = ?', [$request->coupon])
        ->whereDate('to_date', '>', $today)
        ->whereDate('from_date', '<', $today)
        ->where('limit', '>', 0)
        ->where(function ($query) use ($request) {
            $query->where('restaurant_id', $request->restaurantID)
                  ->orWhere('restaurant_id', 0);
        })
        ->first();

    if (!blank($coupon)) {
        $total_used = Discount::where('coupon_id', $coupon->id)
            ->where('status', DiscountStatus::ACTIVE)
            ->count();
    }

    if (blank($coupon)) {
        $msg = 'This Coupon is Invalid';
    } elseif ($coupon->coupon_type == CouponType::VOUCHER && $coupon->restaurant_id != 0 && $coupon->restaurant_id != $request->restaurantID) {
        $msg = 'This Coupon is Invalid';
    } elseif ($total_used >= $coupon->limit) {
        $msg = 'This Coupon is Expired.';
    } else {
        $msg = '';
    }

    if (blank($msg)) {
        return $this->successresponse([
            'success' => 'coupon',
            'msg' => $msg,
            'coupon' => $coupon
        ], 200);
    } else {
        return $this->successresponse([
            'success' => 'coupon',
            'msg' => $msg,
            'coupon' => $coupon
        ], 422);
    }
}

public function generateCoupons(Request $request)
{
    $request->validate([
        'discount_type' => 'required',
        'restaurant_id' => 'required',
        'amount' => 'required',
        'from_date' => 'required|date',
        'to_date' => 'required|date',
        'quantity' => 'required|integer|min:1',
        'prefix' => 'nullable|string|size:5'
    ]);

    GenerateBulkCoupons::dispatch($request->all());

    return response()->json([
        'message' => 'Coupon generation started in background'
    ]);
}

}