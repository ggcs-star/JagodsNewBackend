<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Reservation;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ReservationReportController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Reservation Report';

        $this->middleware(['permission:reservation-report'])->only('index');
    }

    public function index(Request $request)
    {
        return $this->getReservationReport($request);
    }

    public function getReservationReport(Request $request)
    {

        $restaurants = Restaurant::restaurantowner()->get();

        if(request()->ajax())
        {

            $queryArray = [];
            if (!empty($request->restaurant_id)) {
                $queryArray['restaurant_id'] = $request->restaurant_id;
            }

            $dateBetween = [];
            if ($request->from_date != '' && $request->to_date != '') {
                $dateBetween['from_date'] = date('Y-m-d', strtotime($request->from_date)) . ' 00:00:00';
                $dateBetween['to_date']   = date('Y-m-d', strtotime($request->to_date)) . ' 23:59:59';
            }

            if(!blank($dateBetween))
            {
                $reservations = Reservation::with('table','timeSlot')->where($queryArray)->whereBetween('created_at', [$dateBetween['from_date'], $dateBetween['to_date']])->latest()->get();

            } else {
                $reservations = Reservation::with('table','timeSlot')->where($queryArray)->latest()->get();
            }

            return DataTables::of($reservations)
                ->editColumn('restaurant_name', function ($reservation) {
                    return $reservation->table->restaurant->name;
                })
                ->editColumn('table', function ($reservation) {
                    return $reservation->table->name;
                })
                ->editColumn('date', function ($reservation) {
                    return date('d M Y', strtotime($reservation->created_at));
                })
                ->editColumn('slot', function ($reservation) {
                    return date('h:i A', strtotime($reservation->timeSlot->start_time)).'-'.date('h:i A', strtotime($reservation->timeSlot->end_time));
                })
                ->editColumn('guest', function ($reservation) {
                    return $reservation->guest_number;
                })
                ->editColumn('name', function ($reservation) {
                    return $reservation->first_name.' '.$reservation->last_name;
                })
                ->rawColumns([])
                ->make(true);
        }

        return view('admin.report.tableBooking.index', ['restaurants' => $restaurants], $this->data);
    }

}
