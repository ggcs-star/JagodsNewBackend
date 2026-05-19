<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\FrontendController;
use App\Traits\ApiResponse;
use App\Http\Requests\Api\StoreCartRequest;
use App\Http\Requests\Api\ApplyCouponRequest;
use App\Http\Requests\Api\UpdateCartRequest;
use App\Http\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Resources\v1\CartResource;
class CartController extends FrontendController
{
    use ApiResponse;

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        parent::__construct();
        $this->middleware('auth:api');
        $this->data['site_title'] = 'Frontend';
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart(auth()->id());


        if (!$cart) {
            return $this->successresponse([
                'status' => 200,
                'message' => 'Cart is empty',
                'data' => null
            ]);
        }


        return $this->successresponse([
            'status' => 200,
            'message' => 'Cart fetched successfully',
            'data' => new CartResource($cart)
        ]);
    }

    public function store(StoreCartRequest $request)
    {
        try {

            $cart = $this->cartService->addToCart(
                $request->validated(),
                auth()->id()
            );

            return $this->successresponse([

                'status' => 200,

                'message' => 'Added to cart successfully',

                'cart' => $cart
            ]);

        } catch (\Exception $e) {

            return $this->successresponse([

                'status' => $e->getCode() ?: 400,

                'message' => $e->getMessage()
            ]);
        }
    }


    public function update(UpdateCartRequest $request)
    {
        try {
            $cart = $this->cartService->updateCartDetails($request->validated(), auth()->id());
            return $this->successresponse([
                'status' => 200,
                'message' => 'Cart updated successfully',
                'cart' => $cart
            ]);
        } catch (\Exception $e) {
            return $this->successresponse(['status' => 400, 'message' => $e->getMessage()]);
        }
    }
    public function clear()
    {
        try {
            $this->cartService->clearCart(auth()->id());

            return $this->successresponse([
                'status' => 200,
                'message' => 'Cart cleared successfully',
                'data' => []
            ]);
        } catch (\Exception $e) {

            return $this->successresponse([
                'status' => $e->getCode() ?: 400,
                'message' => $e->getMessage()
            ]);
        }
    }
    public function remove($id)
    {
        try {
            $this->cartService->removeItem($id);
            return $this->successresponse(['status' => 200, 'message' => 'Removed successfully']);
        } catch (\Exception $e) {
            return $this->successresponse(['status' => 404, 'message' => $e->getMessage()]);
        }
    }

    public function quantity(Request $request)
    {
        try {
            $data = $this->cartService->updateQuantity($request->cart_item_id, $request->quantity);
            return $this->successresponse(array_merge(['status' => 200], $data));
        } catch (\Exception $e) {
            return $this->successresponse(['status' => 400, 'message' => $e->getMessage()]);
        }
    }

    public function applyCoupon(ApplyCouponRequest $request)
    {
        try {
            $data = $this->cartService->applyCoupon($request->validated(), auth()->id());
            return $this->successresponse([
                'status' => 200,
                'message' => 'Coupon applied successfully',
                'coupon' => $data['coupon'],
                'cart' => $data['cart']
            ]);
        } catch (\Exception $e) {
            return $this->successresponse(['status' => 422, 'message' => $e->getMessage()]);
        }
    }
}