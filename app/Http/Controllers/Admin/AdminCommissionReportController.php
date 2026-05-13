<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\BackendController;
use Yajra\DataTables\DataTables;

class AdminCommissionReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Admin Commission Report';

        $this->middleware(['permission:admin-commission-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getAdminCommissionReport($request);
    }

    public function getAdminCommissionReport(Request $request)
    {
        $restaurants = Restaurant::restaurantowner()->get();

        if (request()->ajax()) {

            $queryArray = [];
            if (!empty($request->restaurant_id)) {
                $queryArray['restaurant_id'] = $request->restaurant_id;
            }

            $queryArray['status'] = OrderStatus::COMPLETED;

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
                ->editColumn('delivery_charge', function ($order) {
                    return $order->delivery_charge;
                })
                ->editColumn('sub_total', function ($order) {
                    return $order->sub_total;
                })
                ->editColumn('total', function ($order) {
                    return $order->total;
                })
                ->editColumn('commission', function ($order) {
                    return number_format((($order->sub_total * setting('order_commission_percentage')) / 100), 2, ".", ",");
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.admincommission.index', ['restaurants' => $restaurants]);
    }
}
