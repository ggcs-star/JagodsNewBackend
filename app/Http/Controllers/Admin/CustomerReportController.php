<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CustomerReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'user Report';

        $this->middleware(['permission:customer-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getCustomerReport($request);
    }

    public function getCustomerReport(Request $request)
    {
        $role                = Role::find(2);
        $users               = User::role($role->name)->latest()->get();

        if (request()->ajax()) {

            $queryArray = [];
            if (!empty($request->user_id)) {
                $queryArray['id'] = $request->user_id;
            }

            $dateBetween = [];
            if ($request->from_date != '' && $request->to_date != '') {
                $dateBetween['from_date'] = Carbon::parse($request->from_date)->startOfDay();
                $dateBetween['to_date']   = Carbon::parse($request->to_date)->endOfDay();
            }

            if (!blank($dateBetween)) {
                $role                = Role::find(2);
                $userdetails         = User::with('orders', 'balance')->role($role->name)->where($queryArray)->whereBetween('created_at', [$dateBetween['from_date'], $dateBetween['to_date']])->latest()->get();
            } else {
                $role                = Role::find(2);
                $userdetails         = User::with('orders', 'balance')->role($role->name)->where($queryArray)->latest()->get();
            }

            return DataTables::of($userdetails)
                ->editColumn('name', function ($userdetail) {
                    return $userdetail->name;
                })
                ->editColumn('email', function ($userdetail) {
                    return $userdetail->email;
                })
                ->editColumn('phone', function ($userdetail) {
                    return $userdetail->phone;
                })
                ->editColumn('total_order', function ($userdetail) {
                    return count($userdetail->orders);
                })
                ->editColumn('balance', function ($userdetail) {
                    return currencyFormat($userdetail->balance->balance);
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.customerReport.index', ['users' => $users], $this->data);
    }

    public function pdf($set_user_id, $set_from_date = '', $set_to_date = '')
    {
        $this->data['showView']      = true;
        $this->data['set_user_id']   = $set_user_id;
        $this->data['set_from_date'] = $set_from_date;
        $this->data['set_to_date']   = $set_to_date;

        if ((int) $set_user_id) {
            $user_id = $set_user_id;
        }

        $dateBetween = [];
        if ($set_from_date != '' && $set_to_date != '') {
            $dateBetween['from_date'] = date('Y-m-d', strtotime($set_from_date)) . ' 00:00:00';
            $dateBetween['to_date']   = date('Y-m-d', strtotime($set_to_date)) . ' 23:59:59';
        }

        if ($user_id) {
            $role                = Role::find(2);
            $this->data['userdetails'] = User::role($role->name)->where('id', $user_id)->latest()->get();
        } elseif (!blank($dateBetween)) {
            $role                = Role::find(2);
            $this->data['userdetails'] = User::role($role->name)->whereBetween('created_at', [$dateBetween['from_date'], $dateBetween['to_date']])->latest()->get();
        } else {
            $role                = Role::find(2);
            $this->data['userdetails'] = User::role($role->name)->latest()->get();
        }

        $pdf = PDF::loadView('admin.report.customerReport.pdf', $this->data);
        return $pdf->download('customerreport-' . date('d-M-Y H:i A') . '.pdf');
    }
}
