<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use Carbon\Carbon;
use App\Models\Expense;
use Yajra\Datatables\Datatables;
use App\Http\Requests\ExpenseRequest;
use Illuminate\Http\Request;

class ExpenseController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Expenses';

        $this->middleware(['permission:expense'])->only('index');
        $this->middleware(['permission:expense'])->only('create', 'store');
        $this->middleware(['permission:expense'])->only('edit', 'update');
        $this->middleware(['permission:expense'])->only('show');
        $this->middleware(['permission:expense'])->only('destroy');
    }

    public function index()
    {
        return view('admin.expense.index', $this->data);
    }

    public function create()
    {
        return view('admin.expense.create');
    }

    public function store(ExpenseRequest $request)
    {
        $parsedDate = Carbon::parse($request->date)->format('Y-m-d');
        $expense = Expense::create([
            'user_id'      => auth()->id(),
            'title'        => $request->title,
            'amount'       => $request->amount,
            'expense_date' => $parsedDate,
        ]);

        if ($request->hasFile('attachment')) {
            $expense->addMediaFromRequest('attachment')->toMediaCollection('expense');
        }

        return redirect()->route('admin.expense.index')->withSuccess('The data inserted successfully.');
    }

    public function download($id)
    {
        $document = Expense::findOrFail($id);
        $media = $document->getFirstMedia('expense');
        if ($media) {
            return  $media;
        }
        abort(404);
    }

    public function edit($id)
    {
        $this->data['expense'] = Expense::findOrFail($id);
        return view('admin.expense.edit', $this->data);
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        // Update expense details
        $expense->update([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'amount' => $request->amount,
            'expense_date' => $request->date,
        ]);

        // Handle file attachment
        if ($request->hasFile('attachment')) {
            $expense->clearMediaCollection('expense'); // Clear existing media directly
            $expense->addMedia($request->file('attachment'))->toMediaCollection('expense');
        }

        return redirect()->route('admin.expense.index')->with('success', 'The data updated successfully.');
    }


    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect(route('admin.expense.index'))->withSuccess('The data deleted successfully.');
    }

    public function getExpense()
    {
        $expenses = Expense::latest()->get();
        $i        = 0;
        return Datatables::of($expenses)
            ->addColumn('action', function ($expense) {
                return action_button([
                    'view'   => ['route' => route('admin.expense.download', $expense),'permission' => 'expense'],
                    'edit'   => ['route' => route('admin.expense.edit', $expense),'permission' => 'expense_edit'],
                    'delete' => ['route' => route('admin.expense.destroy', $expense),'permission' => 'expense_edit'],
                ]);
            })
            ->addColumn('title', function ($expense) {
                return $expense->title;
            })
            ->addColumn('amount', function ($expense) {
                return $expense->amount;
            })
            ->addColumn('date', function ($expense) {
                return Carbon::parse($expense->expense_date)->format('d M Y');;
            })
            ->editColumn('id', function ($user) use (&$i) {
                return ++$i;
            })
            ->escapeColumns([])
            ->make(true);
    }
}
