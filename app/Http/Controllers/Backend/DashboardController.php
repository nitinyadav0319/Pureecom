<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderGroup;
use App\Models\OrderItem;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\Shop;


class DashboardController extends Controller
{
    # admin dashboard
    public function index(Request $request)
{
    $user = auth()->user();
    $isVendor = $user->hasRole('vendor');
 if ($isVendor){
    $shop = Shop::getOrCreateVendorShop($user->id, $user->vendorProfile->business_name);
    $products = \App\Models\Product::where('shop_id', $shop->id)
                ->orderBy('created_at', 'desc')
                ->get();
} else {
    $products = \App\Models\Product::orderBy('created_at', 'desc')->get();
}


    # total sales chart 
    if ($isVendor) {
        $totalSalesChart = $this->totalSalesChartVendor($request->timeline, $user->id);
    } else {
        $totalSalesChart = $this->totalSalesChart($request->timeline);
    }
    $totalSalesData  = $totalSalesChart[0];
    $timelineText    = $totalSalesChart[1];

    # top 5 category sales  
    if ($isVendor) {
        $totalCatSalesData = $this->topFiveCategoryChartVendor($user->id);
    } else {
        $totalCatSalesData = $this->topFiveCategoryChart();
    }

    # last 30 days orders  
    if ($isVendor) {
        $totalOrdersData = $this->last30DaysOrderChartVendor($user->id);
    } else {
        $totalOrdersData = $this->last30DaysOrderChart();
    }

    # this month sales  
    if ($isVendor) {
        $thisMonthSaleData  = $this->thisMonthSaleChartVendor($user->id);
    } else {
        $thisMonthSaleData  = $this->thisMonthSaleChart();
    }

    # --------------------------------------------------------------counters
    $dayStart = Carbon::now()->startOfDay();
    $monthStart = Carbon::now()->startOfMonth();
    $yearStart = Carbon::now()->startOfYear();

    if ($isVendor) {
        // -----------------------------
        // Vendor-specific charts
        // -----------------------------
        $totalSalesChart = $this->totalSalesChartVendor($request->timeline, $user->id);
        $totalSalesData  = $totalSalesChart[0];
        $timelineText    = $totalSalesChart[1];

        $totalCatSalesData = $this->topFiveCategoryChartVendor($user->id);
        $totalOrdersData   = $this->last30DaysOrderChartVendor($user->id);
        $thisMonthSaleData = $this->thisMonthSaleChartVendor($user->id);

        // -----------------------------
        // Vendor-specific counters
        // -----------------------------
        $todayOrderGroupIds = Order::where('vendor_id', $user->id)
            ->where('delivery_status', '!=', orderCancelledStatus())
            ->where('created_at', '>=', $dayStart)
            ->pluck('order_group_id');
        $todayEarning = OrderGroup::whereIn('id', $todayOrderGroupIds)->sum('grand_total_amount');

        $todayPendingOrderGroupIds = Order::where('vendor_id', $user->id)
            ->where('delivery_status', '!=', orderDeliveredStatus())
            ->where('delivery_status', '!=', orderCancelledStatus())
            ->where('created_at', '>=', $dayStart)
            ->pluck('order_group_id');
        $todayPendingEarning = OrderGroup::whereIn('id', $todayPendingOrderGroupIds)->sum('grand_total_amount');

        $thisYearOrderGroupIds = Order::where('vendor_id', $user->id)
            ->where('delivery_status', '!=', orderCancelledStatus())
            ->where('created_at', '>=', $yearStart)
            ->pluck('order_group_id');
        $thisYearEarning = OrderGroup::whereIn('id', $thisYearOrderGroupIds)->sum('grand_total_amount');

        $totalOrderGroupIds = Order::where('vendor_id', $user->id)
            ->where('delivery_status', '!=', orderCancelledStatus())
            ->pluck('order_group_id');
        $totalEarning = OrderGroup::whereIn('id', $totalOrderGroupIds)->sum('grand_total_amount');

        $todaySaleCount = OrderItem::where('vendor_id', $user->id)->where('created_at', '>=', $dayStart)->sum('qty');
        $monthSaleCount = OrderItem::where('vendor_id', $user->id)->where('created_at', '>=', $monthStart)->sum('qty');
        $yearSaleCount  = OrderItem::where('vendor_id', $user->id)->where('created_at', '>=', $yearStart)->sum('qty');

    } else {
        // Admin counters (unchanged)
        $orderGroupIds = Order::where('delivery_status', '!=', orderCancelledStatus())->where('created_at', '>=', $dayStart)->pluck('order_group_id');
        $todayEarning =  OrderGroup::whereIn('id', $orderGroupIds)->sum('grand_total_amount');

        $orderGroupIds = Order::where('delivery_status', '!=', orderDeliveredStatus())
            ->where('delivery_status', '!=', orderCancelledStatus())
            ->where('created_at', '>=', $dayStart)
            ->pluck('order_group_id');
        $todayPendingEarning =  OrderGroup::whereIn('id', $orderGroupIds)->sum('grand_total_amount');

        $orderGroupIds = Order::where('delivery_status', '!=', orderCancelledStatus())
            ->where('created_at', '>=', $yearStart)
            ->pluck('order_group_id');
        $thisYearEarning =  OrderGroup::whereIn('id', $orderGroupIds)->sum('grand_total_amount');

        $orderGroupIds = Order::where('delivery_status', '!=', orderCancelledStatus())->pluck('order_group_id');
        $totalEarning = OrderGroup::whereIn('id', $orderGroupIds)->sum('grand_total_amount');

        $todaySaleCount = OrderItem::where('created_at', '>=', $dayStart)->sum('qty');
        $monthSaleCount = OrderItem::where('created_at', '>=', $monthStart)->sum('qty');
        $yearSaleCount  = OrderItem::where('created_at', '>=', $yearStart)->sum('qty');
    }

    # --------------------------------------------------------------counters

    $values = [
        'totalSalesData'      => $totalSalesData,
        'timelineText'        => $timelineText,
        'totalCatSalesData'   => $totalCatSalesData,
        'totalOrdersData'     => $totalOrdersData,
        'thisMonthSaleData'   => $thisMonthSaleData,
        'todayEarning'        => $todayEarning,
        'todayPendingEarning' => $todayPendingEarning,
        'totalEarning'        => $totalEarning,
        'thisYearEarning'     => $thisYearEarning,

        'todaySaleCount'      => $todaySaleCount,
        'monthSaleCount'      => $monthSaleCount,
        'yearSaleCount'       => $yearSaleCount,
    ];
$values['products'] = $products;
$view = view('backend.pages.dashboard', $values);


     # give permission to the Super admin
        $user = auth()->user();
        if ($user->user_type == 'admin' && $user->hasRole('Super Admin')) {
            return $view;
        } else if ($user->user_type == 'admin') {
            $user->assignRole('Super Admin');
        }
        return $view;
    }

    # admin profile
    public function profile()
    {
        $user = auth()->user();
        return view('backend.pages.profile', compact('user'));
    }

    # admin profile
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->phone = validatePhone($request->phone);
        $user->avatar = $request->avatar;

        if ($request->has('password') && $request->password != '') {
            if ($request->password != $request->password_confirmation) {
                flash(localize('Password confirmation does not match'))->error();
                return back();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        flash(localize('Profile has been updated'))->success();
        return back();
    }

    # total sales chart
    private function totalSalesChart($time)
    {
        $timeline                   = 7; // 7, 30 or 90 days 
        $timelineText               = localize('Last 7 days');

        if ((int)$time > 7) {
            $timeline = (int) $time;
            if ($timeline == 30) {
                $timelineText               = localize('Last 30 days');
            } else {
                $timelineText               = localize('Last 3 months');
            }
        }


        $orderGroupIds = Order::where('delivery_status', '!=', orderCancelledStatus())->where('created_at', '>=', Carbon::now()->subDays($timeline))->pluck('order_group_id');
        $orderGroupsQuery = OrderGroup::whereIn('id', $orderGroupIds)->oldest();
        $totalSalesTimelineInString = '';
        $totalSalesAmountInString   = '';

        for ($i = $timeline; $i >= 0; $i--) {
            $totalSalesAmount = 0;

            foreach ($orderGroupsQuery->get() as $orderGroup) {
                if (date('Y-m-d', strtotime($i . ' days ago')) == date('Y-m-d', strtotime($orderGroup->created_at))) {
                    $totalSalesAmount += $orderGroup->grand_total_amount;
                }
            }

            if ($i == 0) {
                $totalSalesTimelineInString .= json_encode(date('Y-m-d', strtotime($i . ' days ago')));
                $totalSalesAmountInString .= json_encode($totalSalesAmount);
            } else {
                $totalSalesTimelineInString .= json_encode(date('Y-m-d', strtotime($i . ' days ago'))) . ',';
                $totalSalesAmountInString .= json_encode($totalSalesAmount) . ',';
            }
        }

        $totalSalesData         = new SystemSetting; // to create temp instance.
        $totalSalesData->labels =  $totalSalesTimelineInString;
        $totalSalesData->amount = $totalSalesAmountInString;
        $totalSalesData->totalEarning = $orderGroupsQuery->sum('grand_total_amount');

        return [$totalSalesData, $timelineText];
    }

    # top 5 category chart
    private function topFiveCategoryChart()
    {
        $categories = Category::orderBy('total_sale_count', 'DESC')->take(5);
        $totalCategorySalesCount = $categories->sum('total_sale_count');
        $catLabelsInString = '';
        $catSeries = [];

        foreach ($categories->get() as $key => $cat) {
            $catLabelsInString .= json_encode($cat->name);
            if ($key + 1 != 5) {
                $catLabelsInString .= ',';
            }
            array_push($catSeries, (float) $cat->total_sale_count);
        }

        $totalCatSalesData = new SystemSetting; // to create temp instance.
        $totalCatSalesData->totalCategorySalesCount = $totalCategorySalesCount;
        $totalCatSalesData->series = json_encode($catSeries);
        $totalCatSalesData->labels = $catLabelsInString;

        return $totalCatSalesData;
    }

    # last 30 days order
    private function last30DaysOrderChart()
    {
        $timelineOrder                    = 30; // 7, 30 or 90 days   
        $totalOrdersTimelineInString      = '';
        $totalOrdersAmountInString        = '';
        $ordersQuery = Order::where('created_at', '>=', Carbon::now()->subDays($timelineOrder))->oldest();

        for ($j = $timelineOrder; $j >= 0; $j--) {
            $totalOrdersAmount = 0;

            foreach ($ordersQuery->get() as $order) {
                if (date('Y-m-d', strtotime($j . ' days ago')) == date('Y-m-d', strtotime($order->created_at))) {
                    $totalOrdersAmount += 1;
                }
            }

            if ($j == 0) {
                $totalOrdersTimelineInString .= json_encode(date('Y-m-d', strtotime($j . ' days ago')));
                $totalOrdersAmountInString .= json_encode($totalOrdersAmount);
            } else {
                $totalOrdersTimelineInString .= json_encode(date('Y-m-d', strtotime($j . ' days ago'))) . ',';
                $totalOrdersAmountInString .= json_encode($totalOrdersAmount) . ',';
            }
        }

        $totalOrdersData         = new SystemSetting; // to create temp instance.
        $totalOrdersData->labels =  $totalOrdersTimelineInString;
        $totalOrdersData->amount = $totalOrdersAmountInString;
        $totalOrdersData->totalOrders = $ordersQuery->count();

        return $totalOrdersData;
    }

    # this month sale's chart
    private function thisMonthSaleChart()
    {
        $monthStart = Carbon::now()->startOfMonth();
        $orderGroupIds = Order::where('delivery_status', '!=', orderCancelledStatus())->where('created_at', '>=', $monthStart)->pluck('order_group_id');
        $orderGroupsThisMonthQuery =  OrderGroup::whereIn('id', $orderGroupIds)->oldest();
        $thisMonthTimelineInString      = '';
        $thisMonthAmountInString        = '';

        $today = today();
        $dates = [];
        $datesReadable = [];
        for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
            $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
            $datesReadable[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('d M');
        }
        foreach ($dates as $key => $date) {
            $totalOrdersAmount = 0;
            foreach ($orderGroupsThisMonthQuery->get() as $orderGroup) {
                if ($date == date('Y-m-d', strtotime($orderGroup->created_at))) {
                    $totalOrdersAmount += $orderGroup->grand_total_amount;
                }
            }

            if ($key == count($dates) - 1) {
                $thisMonthTimelineInString .= json_encode($datesReadable[$key]);
                $thisMonthAmountInString .= json_encode($totalOrdersAmount);
            } else {
                $thisMonthTimelineInString .= json_encode($datesReadable[$key]) . ',';
                $thisMonthAmountInString .= json_encode($totalOrdersAmount) . ',';
            }
        }
        $thisMonthSaleData         = new SystemSetting; // to create temp instance.
        $thisMonthSaleData->labels =  $thisMonthTimelineInString;
        $thisMonthSaleData->amount = $thisMonthAmountInString;
        $thisMonthSaleData->totalEarning = $orderGroupsThisMonthQuery->sum('grand_total_amount');
        return $thisMonthSaleData;
    }
    /**
 * Vendor-specific total sales chart
 */
private function totalSalesChartVendor($time, $vendorId)
{
    $timeline = 7; // default 7 days
    $timelineText = localize('Last 7 days');

    if ((int)$time > 7) {
        $timeline = (int)$time;
        if ($timeline == 30) {
            $timelineText = localize('Last 30 days');
        } else {
            $timelineText = localize('Last 3 months');
        }
    }

    $orderGroupIds = Order::where('vendor_id', $vendorId)
        ->where('delivery_status', '!=', orderCancelledStatus())
        ->where('created_at', '>=', Carbon::now()->subDays($timeline))
        ->pluck('order_group_id');

    $orderGroupsQuery = OrderGroup::whereIn('id', $orderGroupIds)->oldest();

    $totalSalesTimelineInString = '';
    $totalSalesAmountInString = '';

    for ($i = $timeline; $i >= 0; $i--) {
        $totalSalesAmount = 0;
        foreach ($orderGroupsQuery->get() as $orderGroup) {
            if (date('Y-m-d', strtotime($i . ' days ago')) == date('Y-m-d', strtotime($orderGroup->created_at))) {
                $totalSalesAmount += $orderGroup->grand_total_amount;
            }
        }
        $totalSalesTimelineInString .= json_encode(date('Y-m-d', strtotime($i . ' days ago')));
        $totalSalesAmountInString .= json_encode($totalSalesAmount);
        if ($i != 0) {
            $totalSalesTimelineInString .= ',';
            $totalSalesAmountInString .= ',';
        }
    }

    $totalSalesData = new SystemSetting;
    $totalSalesData->labels = $totalSalesTimelineInString;
    $totalSalesData->amount = $totalSalesAmountInString;
    $totalSalesData->totalEarning = $orderGroupsQuery->sum('grand_total_amount');

    return [$totalSalesData, $timelineText];
}

/**
 * Vendor-specific top 5 category chart
 */
private function topFiveCategoryChartVendor($vendorId)
{
    $categories = Category::whereHas('products', function($q) use ($vendorId) {
        $q->where('vendor_id', $vendorId);
    })->orderBy('total_sale_count', 'DESC')->take(5);

    $totalCategorySalesCount = $categories->sum('total_sale_count');
    $catLabelsInString = '';
    $catSeries = [];

    foreach ($categories->get() as $key => $cat) {
        $catLabelsInString .= json_encode($cat->name);
        if ($key + 1 != 5) $catLabelsInString .= ',';
        $catSeries[] = (float) $cat->total_sale_count;
    }

    $totalCatSalesData = new SystemSetting;
    $totalCatSalesData->totalCategorySalesCount = $totalCategorySalesCount;
    $totalCatSalesData->series = json_encode($catSeries);
    $totalCatSalesData->labels = $catLabelsInString;

    return $totalCatSalesData;
}

/**
 * Vendor-specific last 30 days order chart
 */
private function last30DaysOrderChartVendor($vendorId)
{
    $timelineOrder = 30;
    $totalOrdersTimelineInString = '';
    $totalOrdersAmountInString = '';

    $ordersQuery = Order::where('vendor_id', $vendorId)
        ->where('created_at', '>=', Carbon::now()->subDays($timelineOrder))
        ->oldest();

    for ($j = $timelineOrder; $j >= 0; $j--) {
        $totalOrdersAmount = 0;
        foreach ($ordersQuery->get() as $order) {
            if (date('Y-m-d', strtotime($j . ' days ago')) == date('Y-m-d', strtotime($order->created_at))) {
                $totalOrdersAmount += 1;
            }
        }
        $totalOrdersTimelineInString .= json_encode(date('Y-m-d', strtotime($j . ' days ago')));
        $totalOrdersAmountInString .= json_encode($totalOrdersAmount);
        if ($j != 0) {
            $totalOrdersTimelineInString .= ',';
            $totalOrdersAmountInString .= ',';
        }
    }

    $totalOrdersData = new SystemSetting;
    $totalOrdersData->labels = $totalOrdersTimelineInString;
    $totalOrdersData->amount = $totalOrdersAmountInString;
    $totalOrdersData->totalOrders = $ordersQuery->count();

    return $totalOrdersData;
}

/**
 * Vendor-specific this month sale chart
 */
private function thisMonthSaleChartVendor($vendorId)
{
    $monthStart = Carbon::now()->startOfMonth();

    $orderGroupIds = Order::where('vendor_id', $vendorId)
        ->where('delivery_status', '!=', orderCancelledStatus())
        ->where('created_at', '>=', $monthStart)
        ->pluck('order_group_id');

    $orderGroupsThisMonthQuery = OrderGroup::whereIn('id', $orderGroupIds)->oldest();

    $thisMonthTimelineInString = '';
    $thisMonthAmountInString = '';

    $today = today();
    $dates = [];
    $datesReadable = [];
    for ($i = 1; $i <= $today->daysInMonth; $i++) {
        $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
        $datesReadable[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('d M');
    }

    foreach ($dates as $key => $date) {
        $totalOrdersAmount = 0;
        foreach ($orderGroupsThisMonthQuery->get() as $orderGroup) {
            if ($date == date('Y-m-d', strtotime($orderGroup->created_at))) {
                $totalOrdersAmount += $orderGroup->grand_total_amount;
            }
        }
        $thisMonthTimelineInString .= json_encode($datesReadable[$key]);
        $thisMonthAmountInString .= json_encode($totalOrdersAmount);
        if ($key != count($dates) - 1) {
            $thisMonthTimelineInString .= ',';
            $thisMonthAmountInString .= ',';
        }
    }

    $thisMonthSaleData = new SystemSetting;
    $thisMonthSaleData->labels = $thisMonthTimelineInString;
    $thisMonthSaleData->amount = $thisMonthAmountInString;
    $thisMonthSaleData->totalEarning = $orderGroupsThisMonthQuery->sum('grand_total_amount');

    return $thisMonthSaleData;
}


}

