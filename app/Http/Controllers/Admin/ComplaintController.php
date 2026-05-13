<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Bank;
use App\Models\Report;
use App\Models\Restaurant;
use App\Enums\ReportStatus;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Requests\BankRequest;
use App\Http\Services\ComplaintService;
use App\Notifications\OrderReportResponse;
use App\Http\Controllers\BackendController;

class ComplaintController extends BackendController
{

    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Complaints';
        $this->middleware(['permission:complaints']);
    }

    public function index()
    {
        return view('admin.complaint.index', $this->data);
    }


    public function show($id)
    {
        $report = Report::findOrFail($id);
        return view('admin.complaint.view', compact('report'));
    }

    public function destroy($id)
    {
        Report::findOrFail($id)->delete();
        return redirect(route('admin.complaints.index'))->withSuccess('The data deleted successfully.');
    }

    public function getComplaint(Request $request)
    {
        if (request()->ajax()) {
            $queryArray = [];
            if (!empty($request->status) && (int) $request->status) {
                $queryArray['status'] = $request->status;
                $reports = Report::where($queryArray)->latest()->get();
            } else {
                $reports = Report::where($queryArray)->latest()->get();
            }

            $i = 0;
            return Datatables::of($reports)
                ->addColumn('action', function ($report) {

                    return action_button([
                        'view'   => ['route' => route('admin.complaints.show', $report),'permission' => 'complaints'],
                        'delete' => ['route' => route('admin.complaints.destroy', $report),'permission' => 'complaints'],
                    ]);
                })
                ->editColumn('id', function ($bank) use (&$i) {
                    return ++$i;
                })

                ->editColumn('order_code', function ($report) {
                    return $report->order->order_code;
                })

                ->editColumn('user_name', function ($report) {
                    return $report->user->name;
                })

                ->editColumn('status', function ($report) {
                    return $report->statusName;
                })
                ->escapeColumns([])
                ->make(true);
        }
    }

    public function changeStatus($id, $status)
    {
        $report = app(ComplaintService::class)->changeStatus($id, $status);
        if ($report) {
            try {
                $report->order->user->notify(new OrderReportResponse($report->order,$report->status));
                return redirect()->route('admin.complaints.index')->withSuccess('The Status Change successfully!');
            } catch (\Exception $e) {
            }
        }else{
            return view('errors.404');
        }
    }
}
