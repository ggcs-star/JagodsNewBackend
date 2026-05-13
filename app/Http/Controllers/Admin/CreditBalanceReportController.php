<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class CreditBalanceReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Credit Balance Report';
        $this->middleware(['permission:credit-balance-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getCreditBalanceReport($request);
    }

    public function getCreditBalanceReport(Request $request)
    {

        $this->data['roles']       = Role::all();
        $this->data['users']       = User::All();

        if ($request->role_id) {
            $role = $request->post('role_id');
            if (((int)$role)) {
                $role                = Role::find($role);
                $this->data['users'] = User::role($role->name)->latest()->get();
            }
        }

        if (request()->ajax()) {

            if ($request->role_id && $request->user_id) {
                $role                         = Role::find($request->role_id);
                $creditBalances = User::with('roles', 'media', 'balance', 'getrole')->role($role->name)->where('id', $request->user_id)->latest()->get();
            } elseif ($request->user_id) {
                $creditBalances = User::with('roles', 'media', 'balance', 'getrole')->where('id', $request->user_id)->latest()->get();
            } elseif ($request->role_id) {
                $role                         = Role::find($request->role_id);
                $creditBalances = User::with('roles', 'media', 'balance', 'getrole')->role($role->name)->latest()->get();
                $this->data['users']          = User::with('roles', 'media', 'balance', 'getrole')->role($role->name)->latest()->get();
            } else {
                $creditBalances = User::with('roles', 'media', 'balance', 'getrole')->get();
            }

            return DataTables::of($creditBalances)
                ->editColumn('name', function ($creditBalance) {
                    return $creditBalance->name;
                })
                ->editColumn('user_role', function ($creditBalance) {
                    return $creditBalance->getrole->name;
                })
                ->editColumn('phone', function ($creditBalance) {
                    return $creditBalance->phone;
                })
                ->editColumn('credit', function ($creditBalance) {
                    return currencyFormat($creditBalance->balance->balance);
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.creditBalance.index', $this->data);
    }

    public function getUsers(Request $request)
    {
        $role = $request->get('role');
        if (((int)$role)) {
            $role  = Role::find($role);
            $users = User::with('roles', 'media', 'balance')->role($role->name)->latest()->get();
            if (!blank($users)) {
                $select = '--';
                echo "<option value=''>" . $select . "</option>";
                foreach ($users as $user) {
                    if ($user->phone) {
                        echo "<option value='" . $user->id . "'>" . $user->name . ' ' . '(' . $user->phone . ')' . "</option>";
                    } else {
                        echo "<option value='" . $user->id . "'>" . $user->name . "</option>";
                    }
                }
            }
        }
    }
}
