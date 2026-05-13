<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Withdraw;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class WithdrawReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Withdraw Report';
        $this->middleware(['permission:withdraw-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getWithdrawReport($request);
    }

    public function getWithdrawReport(Request $request)
    {

        $users = User::role([
            UserRole::RESTAURANTOWNER,
            UserRole::DELIVERYBOY
        ])->latest()->get();

        if (request()->ajax()) {

            $queryArray = [];
            if (!empty($request->user_id)) {
                $queryArray['user_id'] = $request->user_id;
            }

            $dateBetween = [];
            if ($request->from_date != '' && $request->to_date != '') {
                $dateBetween['from_date'] = date('Y-m-d', strtotime($request->from_date)) . ' 00:00:00';
                $dateBetween['to_date']   = date('Y-m-d', strtotime($request->to_date)) . ' 23:59:59';
            }

            if (!blank($dateBetween)) {
                $withdraws = Withdraw::with('user')->where($queryArray)->whereBetween('created_at', [$dateBetween['from_date'], $dateBetween['to_date']])->get();
            } else {
                $withdraws = Withdraw::with('user')->where($queryArray)->latest()->get();
            }

            return DataTables::of($withdraws)
                ->editColumn('name', function ($withdraw) {
                    return $withdraw->user->name;
                })
                ->editColumn('date', function ($withdraw) {
                    return date('d-m-Y', strtotime($withdraw->date));
                })
                ->editColumn('amount', function ($withdraw) {
                    return currencyFormat($withdraw->amount);
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.withdrawreport.index', ['users' => $users], $this->data);
    }
}
