<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RestaurantOwnerSalesReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Restaurant Owner Sales Report';

        $this->middleware(['permission:restaurant-owner-sales-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getRestaurantOwnerSalesReport($request);
    }

    public function getRestaurantOwnerSalesReport(Request $request)
    {
        $restaurants = Restaurant::restaurantowner()->latest()->get();

        if (request()->ajax()) {
            $queryArray = [];
            if (!empty($request->restaurant_id)) {
                $queryArray['restaurant_id'] = $request->restaurant_id;
            }

            $dateBetween = [];
            if ($request->from_date != '' && $request->to_date != '') {
                $dateBetween['from_date'] = date('Y-m-d', strtotime($request->from_date)) . ' 00:00:00';
                $dateBetween['to_date']   = date('Y-m-d', strtotime($request->to_date)) . ' 23:59:59';
            }

            if (!blank($dateBetween)) {
                $orders = Order::with('restaurant')->where($queryArray)->whereBetween('created_at', [$dateBetween['from_date'], $dateBetween['to_date']])->get();
            } else {
                $orders = Order::with('restaurant')->where($queryArray)->get();
            }

            return DataTables::of($orders)
                ->editColumn('order_code', function ($order) {
                    return $order->order_code;
                })
                ->editColumn('restaurant_name', function ($order) {
                    return optional($order->restaurant)->name;
                })
                ->editColumn('order_status', function ($order) {
                    return $order->statusName;
                })
                ->editColumn('order_type', function ($order) {
                    return $order->getOrderTypeName;
                })
                ->editColumn('delivery_charge', function ($order) {
                    return $order->delivery_charge;
                })
                ->editColumn('sub_total', function ($order) {
                    return $order->sub_total;
                })
                ->editColumn('total', function ($order) {
                    return $order->total;
                })
                ->editColumn('payment_status', function ($order) {
                    return $order->paymentStatusName;
                })
                ->editColumn('payment_method', function ($order) {
                    return $order->getPaymentMethod;
                })
                ->editColumn('paid_amount', function ($order) {
                    return $order->paid_amount;
                })
                ->rawColumns(['order_status', 'order_type', 'payment_status'])
                ->make(true);
        }

        return view('admin.report.restaurant_owner_sales.index', ['restaurants' => $restaurants]);
    }
}
