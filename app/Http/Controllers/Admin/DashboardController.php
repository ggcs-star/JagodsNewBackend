<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Enums\UserStatus;
use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Restaurant;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Enums\RestaurantStatus;
use App\Enums\DeliveryHistoryStatus;
use App\Enums\OrderTypeStatus;
use App\Enums\PaymentStatus;
use App\Models\DeliveryStatusHistories;
use App\Http\Controllers\BackendController;
use App\Models\OrderLineItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Permission\Models\Role;

class DashboardController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['siteTitle'] = 'Dashboard';
        $this->middleware(['permission:dashboard'])->only('index');
    }

    public function index()
    {

        $this->months();
        $user             = auth()->user();
        $role             = Role::find(UserRole::CUSTOMER);

        if (auth()->user()->myrole == UserRole::ADMIN) {
            $this->data['topDeliveryMan']  = Order::with('delivery')->where(['order_type' => OrderTypeStatus::DELIVERY, 'status' => OrderStatus::COMPLETED, 'payment_status' => PaymentStatus::PAID])
                ->groupBy('delivery_boy_id')
                ->selectRaw('delivery_boy_id, COUNT(*) as total_orders')
                ->orderByDesc('total_orders')
                ->take(8)
                ->get();

            $this->data['topRestaurants']  = Order::with('restaurant')->where([
                'status' => OrderStatus::COMPLETED,
                'payment_status' => PaymentStatus::PAID
            ])
                ->groupBy('restaurant_id')
                ->selectRaw('restaurant_id, COUNT(*) as total_orders')
                ->orderByDesc('total_orders')
                ->take(8)
                ->get();
        }

        if (auth()->user()->myrole == UserRole::RESTAURANTOWNER && auth()->user()->restaurant->id) {
            $this->data['mostPopularItems'] = OrderLineItem::with('menuItem')
                ->when(
                    $user->myrole === UserRole::RESTAURANTOWNER && $user->restaurant?->id,
                    fn($query) => $query->where('restaurant_id', $user->restaurant->id)
                )
                ->groupBy('menu_item_id')
                ->selectRaw('menu_item_id, SUM(quantity) as total_quantity')
                ->orderByDesc('total_quantity')
                ->take(8)
                ->get();

            $this->data['ownerTotalOrders']        = Order::orderBy('id', 'desc')->where(['restaurant_id' => auth()->user()->restaurant->id])->count() ?? 0;
            $this->data['ownerNotificationOrders'] = Order::with('user')->orderBy('id', 'desc')->where('restaurant_id', auth()->user()->restaurant->id)->where('status', OrderStatus::PENDING)->count() ?? 0;
            $this->data['ownerTotalReservations']  = Reservation::orderBy('id', 'desc')->where('restaurant_id', auth()->user()->restaurant->id)->count() ?? 0;
        }
        if (auth()->user()->myrole == UserRole::DELIVERYBOY) {
            $activeOrders          = Order::where('delivery_boy_id', auth()->id())->where('order_type', OrderTypeStatus::DELIVERY)->where('status', '!=', OrderStatus::COMPLETED)->orderBy('updated_at', 'desc')->withCount('items')->take(8)->get();
            $totalEarningToday     = Order::where('delivery_boy_id', auth()->id())->where('order_type', OrderTypeStatus::DELIVERY)->whereBetween('created_at', [Carbon::today()->startOfDay(), Carbon::today()->endOfDay()])->where('status', OrderStatus::COMPLETED)->sum('delivery_charge');
            $totalEarningThisWeek  = Order::where('delivery_boy_id', auth()->id())->where('order_type', OrderTypeStatus::DELIVERY)->whereBetween('created_at', [Carbon::today()->subDays(6)->startOfDay(), Carbon::today()->endOfDay()])->where('status', OrderStatus::COMPLETED)->sum('delivery_charge');
            $totalEarningThisMonth = Order::where('delivery_boy_id', auth()->id())->where('order_type', OrderTypeStatus::DELIVERY)->whereBetween('created_at', [Carbon::today()->subDays(29)->startOfDay(), Carbon::today()->endOfDay()])->where('status', OrderStatus::COMPLETED)->sum('delivery_charge');

            $this->data['deliveryBoy_balance']          = auth()->user()->balance->balance > 0 ? auth()->user()->balance->balance : 0;
            $this->data['deliveryBoy_earningToday']     = setting('currency_code') . ' ' . round($totalEarningToday, 2);
            $this->data['deliveryBoy_earningThisWeek']  = setting('currency_code') . ' ' . round($totalEarningThisWeek, 2);
            $this->data['deliveryBoy_earningThisMonth'] = setting('currency_code') . ' ' . round($totalEarningThisMonth, 2);
            $this->data['deliveryBoyActiveOrders']      = $activeOrders;
        }

        $deliveryStatusHistoriesArray = DeliveryStatusHistories::where(['user_id' => auth()->user()->id, 'status'  => DeliveryHistoryStatus::CANCEL])->get()->pluck('order_id')->toArray();
        $notificationOrders           = Order::with('user')->where(['delivery_boy_id' => null])->whereIn('status', [OrderStatus::ACCEPT, OrderStatus::PROCESS])->latest()->whereNotIn('id', $deliveryStatusHistoriesArray)->get();

        $this->data['topCustomers'] = Order::with('user')->where(['status' => OrderStatus::COMPLETED, 'payment_status' => PaymentStatus::PAID])->when($user->myrole === UserRole::RESTAURANTOWNER && $user->restaurant?->id, fn($query) => $query->where('restaurant_id', $user->restaurant->id))
            ->groupBy('user_id')->selectRaw('count(*) as total_orders, user_id')->orderByDesc('total_orders')->take(8)->get();

        $monthWiseTotalIncome    = [];
        $monthDayWiseTotalIncome = [];
        $monthWiseTotalOrder     = [];
        $monthDayWiseTotalOrder  = [];
        $yearlyOrders        = Order::with('user')->orderBy('id', 'desc')->where('status', '!=', OrderStatus::CANCEL)->whereYear('created_at', date('Y'))->orderowner()->get();

        if (!blank($yearlyOrders)) {
            foreach ($yearlyOrders as $yearlyOrder) {
                $monthNumber = (int) date('m', strtotime($yearlyOrder->created_at));
                $dayNumber   = (int) date('d', strtotime($yearlyOrder->created_at));
                if (!isset($monthDayWiseTotalIncome[$monthNumber][$dayNumber])) {
                    $monthDayWiseTotalIncome[$monthNumber][$dayNumber] = 0;
                }
                $monthDayWiseTotalIncome[$monthNumber][$dayNumber] += $yearlyOrder->paid_amount;
                if (!isset($monthWiseTotalIncome[$monthNumber])) {
                    $monthWiseTotalIncome[$monthNumber] = 0;
                }
                if (auth()->user()->myrole == 4) {
                    $monthWiseTotalIncome[$monthNumber] += $yearlyOrder->delivery_charge;
                } else {
                    $monthWiseTotalIncome[$monthNumber] += $yearlyOrder->paid_amount;
                }
                if (!isset($monthDayWiseTotalOrder[$monthNumber][$dayNumber])) {
                    $monthDayWiseTotalOrder[$monthNumber][$dayNumber] = 0;
                }
                $monthDayWiseTotalOrder[$monthNumber][$dayNumber] += 1;
                if (!isset($monthWiseTotalOrder[$monthNumber])) {
                    $monthWiseTotalOrder[$monthNumber] = 0;
                }
                $monthWiseTotalOrder[$monthNumber] += 1;
            }
        }

        $this->data['monthWiseTotalIncome']    = $monthWiseTotalIncome;
        $this->data['monthDayWiseTotalIncome'] = $monthDayWiseTotalIncome;
        $this->data['monthWiseTotalOrder']     = $monthWiseTotalOrder;
        $this->data['monthDayWiseTotalOrder']  = $monthDayWiseTotalOrder;
        $this->data['totalOrders']             = Order::count();
        $this->data['totalUsers']              = User::role($role->name)->where(['status' => UserStatus::ACTIVE])->latest()->count();
        $this->data['totalRestaurants']        = Restaurant::where(['status' => RestaurantStatus::ACTIVE])->count();
        $this->data['notificationOrders']      = count($notificationOrders);
        $this->data['yearlyOrders']            = count($yearlyOrders);
        $this->data['totalDaliveryOrders']     = Order::with('user')->where(['delivery_boy_id' => auth()->user()->id])->latest()->count();
        $this->data['totalIncome']             = Order::where(['status' => OrderStatus::COMPLETED])->sum('paid_amount');
        $this->data['recentOrders']            = Order::with('user')->orderBy('id', 'desc')->whereDate('created_at', date('Y-m-d'))->orderowner()->get();
        $this->data['userCredit']              = currencyFormat(auth()->user()->balance->balance > 0 ? auth()->user()->balance->balance : 0);
        
        return view('admin.dashboard.index', $this->data);
    }

    public function dayWiseIncomeOrder(Request $request)
    {
        $type          = $request->type;
        $monthID       = $request->monthID;
        $dayWiseData   = $request->dayWiseData;
        $showChartData = [];
        if ($type && $monthID) {
            $days        = date('t', mktime(0, 0, 0, $monthID, 1, date('Y')));
            $dayWiseData = json_decode($dayWiseData, true);
            for ($i = 1; $i <= $days; $i++) {
                $showChartData[$i] = isset($dayWiseData[$i]) ? $dayWiseData[$i] : 0;
            }
        } else {
            for ($i = 1; $i <= 31; $i++) {
                $showChartData[$i] = 0;
            }
        }
        echo json_encode($showChartData);
    }

    public function dayWiseOrderStatistics(Request $request)
    {
        $startDate                          = Carbon::parse($request->start_date)->startOfDay();
        $endDate                            = Carbon::parse($request->end_date)->endOfDay();
        if (auth()->user()->myrole == UserRole::ADMIN) {
            $order_statistics['total']      = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            $order_statistics['pending']    = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::PENDING)->count();
            $order_statistics['processing'] = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::PROCESS)->count();
            $order_statistics['onTheWay']   = Order::whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::ON_THE_WAY)->count();
            $order_statistics['delivered']  = Order::whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::COMPLETED)->count();
            $order_statistics['cancel']     = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::CANCEL)->count();
            $order_statistics['reject']     = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::REJECT)->count();
        } elseif (auth()->user()->myrole == UserRole::RESTAURANTOWNER && auth()->user()->restaurant->id) {
            $restaurantId                   = auth()->user()->restaurant->id;
            $order_statistics['total']      = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->count();
            $order_statistics['pending']    = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::PENDING)->count();
            $order_statistics['processing'] = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::PROCESS)->count();
            $order_statistics['onTheWay']   = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::ON_THE_WAY)->count();
            $order_statistics['delivered']  = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::COMPLETED)->count();
            $order_statistics['cancel']     = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::CANCEL)->count();
            $order_statistics['reject']     = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::REJECT)->count();
        } elseif (auth()->user()->myrole == UserRole::DELIVERYBOY) {

            $myDeliveryOrders = Order::where('delivery_boy_id', auth()->id());
            $totalEarning = $myDeliveryOrders->whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::COMPLETED)->sum('delivery_charge');
            $order_statistics['deliveryBoy_totalEarnings']        = setting('currency_code') . ' ' . round($totalEarning, 2);
            $order_statistics['deliveryBoy_totalAcceptedOrders']  = $myDeliveryOrders->whereBetween('created_at', [$startDate, $endDate])->count();
            $order_statistics['deliveryBoy_totalCompletedOrders'] = $myDeliveryOrders->whereBetween('created_at', [$startDate, $endDate])->where('order_type', OrderTypeStatus::DELIVERY)->where('status', OrderStatus::COMPLETED)->count();
        }
        return json_encode($order_statistics);
    }

    public function dayWiseSalesStatistics(Request $request)
    {
        $startDate                     = Carbon::parse($request->start_date)->startOfDay();
        $endDate                       = Carbon::parse($request->end_date)->endOfDay();
        $days                          = $startDate->diffInDays($endDate) + 1;
        if (auth()->user()->myrole == UserRole::RESTAURANTOWNER && auth()->user()->restaurant->id) {
            $restaurantId    = auth()->user()->restaurant->id;
            $shortStatistics = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('COUNT(*) as totalSales, COUNT(*) / ? as averageSalesPerDay', [$days])->first();
            $salesByDay      = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('DATE(created_at) as sale_date, COUNT(*) as totalSales')->groupBy('sale_date')->orderBy('sale_date', 'ASC')->get();
        } else if (auth()->user()->myrole == UserRole::ADMIN) {
            $shortStatistics               = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('COUNT(*) as totalSales, COUNT(*) / ? as averageSalesPerDay', [$days])->first();
            $salesByDay                    = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('DATE(created_at) as sale_date, COUNT(*) as totalSales')->groupBy('sale_date')->orderBy('sale_date', 'ASC')->get();
        }
        $sale_statistics['totalSales'] = $shortStatistics->totalSales;
        $sale_statistics['avgSales']   = round($shortStatistics->averageSalesPerDay, 2);
        $allDates                      = [];
        $allSales                      = [];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $allDates[]    = $date->format('d');

            $sales      = $salesByDay->where('sale_date', $formattedDate)->first();
            $allSales[] = $sales->totalSales ?? 0;
        }

        $sale_statistics['dates']      = $allDates;
        $sale_statistics['sales']      = $allSales;

        return json_encode($sale_statistics);
    }

    public function dayWiseOrderSummery(Request $request)
    {
        $startDate                = Carbon::parse($request->start_date)->startOfDay();
        $endDate                  = Carbon::parse($request->end_date)->endOfDay();
        $keys                     = [];
        $values                   = [];
        if (auth()->user()->myrole == UserRole::RESTAURANTOWNER && auth()->user()->restaurant->id) {
            $restaurantId             = auth()->user()->restaurant->id;
            $totalOrders              = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->count();
            $delivered                = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->count();
            $canceled                 = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::CANCEL)->count();
            $rejected                 = Order::where('restaurant_id', $restaurantId)->whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::REJECT)->count();
        } else if (auth()->user()->myrole == UserRole::ADMIN) {
            $totalOrders              = Order::whereBetween('created_at', [$startDate, $endDate])->count();
            $delivered                = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->count();
            $canceled                 = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::CANCEL)->count();
            $rejected                 = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::REJECT)->count();
        }
        $Parcentages['delivered'] = $totalOrders != 0 ? round(($delivered / $totalOrders) * 100, 2) : 0;
        $Parcentages['canceled']  = $totalOrders != 0 ? round(($canceled / $totalOrders) * 100, 2) : 0;
        $Parcentages['rejected']  = $totalOrders != 0 ? round(($rejected / $totalOrders) * 100, 2) : 0;
        foreach ($Parcentages as $key => $value) {
            $keys[]   = ucfirst($key);
            $values[] = $value;
        }
        $orderSummaryStatistics['keys']   = $keys;
        $orderSummaryStatistics['values'] = $values;
        return json_encode([
            'summary' => $orderSummaryStatistics,
            'total'   => $totalOrders ?? 0,
        ]);
    }

    public function dayWiseRevenueStatistics(Request $request)
    {
        $startDate         = Carbon::parse($request->start_date)->startOfDay();
        $endDate           = Carbon::parse($request->end_date)->endOfDay();
        $days              = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $sameMonth         = Carbon::parse($startDate)->format('F') == Carbon::parse($endDate)->format('F');
        $daysInMonth       = Carbon::parse($startDate)->daysInMonth;
        $revenueStatistics = [];

        if (auth()->user()->myrole == UserRole::RESTAURANTOWNER && auth()->user()->restaurant->id) {
            $data = auth()->user()->restaurant->where('user_id', auth()->id())->get();
            $hourList         = [];
            $orderByHour      = [];
            foreach ($data as $key => $restaurant) {
                $hourLists = CarbonPeriod::create(Carbon::parse($restaurant->opening_time), '1 hour', Carbon::parse($restaurant->closing_time));
                $orderActivity = Order::where('restaurant_id', $restaurant->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->selectRaw('hour(created_at) as hour, count(*) as orderCount')
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
                foreach ($hourLists as $key => $hour) {
                    $hourList[]         = $hour->format('H');
                    $orderByHour[]      = $orderActivity->where('hour', $hour->format('H'))->first()->orderCount ?? 0;
                }
            }
            $revenueStatistics = (object) [
                'scale'   => $hourList,
                'data'    => $orderByHour,
                'revenue' => false,
            ];
        } elseif (auth()->user()->myrole == UserRole::ADMIN) {
            if (($days == $daysInMonth && $sameMonth) || $days <= 31) {
                $data      = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('date(created_at) date, COALESCE(SUM(total), 0) total, count(*) data')->groupBy('date')->orderBy('date', 'desc')->get();
                $period    = CarbonPeriod::since($startDate)->day()->until($endDate);
                $allDates  = [];
                $allSales  = [];
                foreach ($period as $date) {
                    $allDates[] = $date->format('d');

                    $sales      = $data->where('date', $date->format('Y-m-d'))->first();
                    $allSales[] = round($sales->total ?? 0, 2);
                }
            } else {
                $startDate = $startDate->startOfMonth()->format('Y-m-d');
                $endDate   = $endDate->endOfMonth()->format('Y-m-d');
                $data      = Order::whereBetween('created_at', [$startDate, $endDate])->where('status', OrderStatus::COMPLETED)->where('payment_status', PaymentStatus::PAID)->selectRaw('year(created_at) year, monthname(created_at) month, sum(total) total, count(*) data')->groupBy('year', 'month')->orderBy('year', 'desc')->get();

                $period = CarbonPeriod::since($startDate)->months()->until($endDate);
                $allDates = [];
                $allSales = [];
                foreach ($period as $date) {
                    $allDates[] = $date->format('M');

                    $sales      = $data->where('year', $date->format('Y'))->where('month', $date->format('F'))->first();
                    $allSales[] = round($sales->total ?? 0, 2);
                }
            }
            $revenueStatistics = (object) [
                'scale'   => $allDates,
                'data'    => $allSales,
                'revenue' => true,
            ];
        }

        return json_encode($revenueStatistics);
    }

    private function months()
    {
        $this->data['months'] = [
            1 => 'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December',
        ];
    }
}
