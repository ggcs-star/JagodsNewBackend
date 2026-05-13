<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Bank;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BankRequest;
use App\Http\Controllers\BackendController;

class BankController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Bank';

        $this->middleware(['permission:bank'])->only('index');
        $this->middleware(['permission:bank_create'])->only('create', 'store');
        $this->middleware(['permission:bank_edit'])->only('edit', 'update');
        $this->middleware(['permission:bank_show'])->only('show');
        $this->middleware(['permission:bank_delete'])->only('destroy');
    }

    public function index()
    {
        $restaurants=Restaurant::with('user')->latest()->get();
        $this->data['restaurants']     = $restaurants;
        return view('admin.bank.index', $this->data);
    }

    public function create()
    {
        $this->data['users'] = User::role([3, 4])->with('roles')->latest()->get();
        return view('admin.bank.create', $this->data);
    }


    public function store(BankRequest $request)
    {
        $bank                      = new Bank;
        $bank->user_id             = $request->user_id;
        $bank->bank_name           = $request->bank_name;
        $bank->bank_code           = $request->bank_code;
        $bank->recipient_name      = $request->recipient_name;
        $bank->account_number      = $request->account_number;
        $bank->mobile_agent_name   = $request->mobile_agent_name;
        $bank->mobile_agent_number = $request->mobile_agent_number;
        $bank->paypal_id           = $request->paypal_id;
        $bank->upi_id              = $request->upi_id;
        $bank->save();
        return redirect(route('admin.bank.index'))->withSuccess('The data inserted successfully.');
    }


    public function edit($id)
    {
        $this->data['users'] = User::role([3, 4])->with('roles')->latest()->get();
        $this->data['bank'] = Bank::findOrFail($id);
        return view('admin.bank.edit', $this->data);
    }


    public function update(BankRequest $request, Bank $bank)
    {
        $bank->user_id             = $request->user_id;
        $bank->bank_name           = $request->bank_name;
        $bank->bank_code           = $request->bank_code;
        $bank->recipient_name      = $request->recipient_name;
        $bank->account_number      = $request->account_number;
        $bank->mobile_agent_name   = $request->mobile_agent_name;
        $bank->mobile_agent_number = $request->mobile_agent_number;
        $bank->paypal_id           = $request->paypal_id;
        $bank->upi_id              = $request->upi_id;
        $bank->save();
        return redirect(route('admin.bank.index'))->withSuccess('The data updated successfully.');
    }

    public function show(Bank $bank)
    {
        return view('admin.bank.show', compact('bank'));
    }

    public function destroy($id)
    {
        Bank::findOrFail($id)->delete();
        return redirect(route('admin.bank.index'))->withSuccess('The data deleted successfully.');
    }

    public function getBank(Request $request)
    {
        if (request()->ajax()) {


            $auth = auth();
                $banks = Bank::where(function($query) use($auth, $request) {
                    if($auth->user()->myrole != 1) {
                        return $query->where('user_id', $auth->user()->id);
                    } else {
                        if($request->user_id != 0) {
                            return $query->where('user_id', $request->user_id);
                        }
                    }
                })->latest()->get();

            $i = 0;
            return Datatables::of($banks)
                ->addColumn('action', function ($bank) {
                    return action_button([
                        'view'   => ['route' => route('admin.bank.show', $bank),'permission' => 'bank_edit'],
                        'edit'   => ['route' => route('admin.bank.edit', $bank),'permission' => 'bank_edit'],
                        'delete' => ['route' => route('admin.bank.destroy', $bank),'permission' => 'bank_delete'],
                    ]);
                })
                ->editColumn('id', function ($bank) use (&$i) {
                    return ++$i;
                })

                ->editColumn('bank_name', function ($bank) {
                    return $bank->bank_name;
                })

                ->editColumn('account_number', function ($bank) {
                    return $bank->account_number;
                })

                ->editColumn('mobile_agent_name', function ($bank) {
                    return $bank->mobile_agent_name;
                })

                ->escapeColumns([])
                ->make(true);
        }
    }
}
