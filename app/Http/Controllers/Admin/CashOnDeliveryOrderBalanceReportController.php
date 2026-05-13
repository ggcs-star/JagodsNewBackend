<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\DeliveryBoyAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CashOnDeliveryOrderBalanceReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Cash On delivery Order Balance Report';
        $this->middleware(['permission:cash-on-delivery-order-balance-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getCashOnDeliveryOrderReport($request);
    }

    public function getCashOnDeliveryOrderReport(Request $request)
    {
        $role = Role::find(4);
        $users = User::role($role->name)
            ->latest()
            ->get();

        if (request()->ajax()) {
            $queryArray = [];
            if (!empty($request->user_id)) {
                $queryArray['user_id'] = $request->user_id;
            }

            if (!blank($queryArray)) {
                $deliveryBoyAccounts = DeliveryBoyAccount::where($queryArray)->latest()->get();
            } else {
                $deliveryBoyAccounts = DeliveryBoyAccount::all();
            }

            return DataTables::of($deliveryBoyAccounts)
                ->editColumn('name', function ($deliveryBoyAccount) {
                    return $deliveryBoyAccount->user->name;
                })
                ->editColumn('phone', function ($deliveryBoyAccount) {
                    return $deliveryBoyAccount->user->phone;
                })
                ->editColumn('delivery_commision', function ($deliveryBoyAccount) {
                    return currencyFormat($deliveryBoyAccount->delivery_charge > 0 ? $deliveryBoyAccount->delivery_charge : 0);
                })
                ->editColumn('order_amount', function ($deliveryBoyAccount) {
                    return currencyFormat($deliveryBoyAccount->balance > 0 ? $deliveryBoyAccount->balance : 0);
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.cashDeliveryOrderBalance.index', ['users' => $users], $this->data);
    }
}
