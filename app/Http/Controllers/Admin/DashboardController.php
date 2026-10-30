<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Common;
use App\Models\Page;
use App\Models\Category;
use App\Models\Customer;
use App\Models\DeliveryBoy;
use App\Models\DeliveryWithdrawal;
use App\Models\Hashtag;
use App\Models\Items;
use App\Models\Offers;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Review;
use App\Models\SellerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    private $folder_brand = "brand";
    private $folder_customer = "customer";
    private $folder_product = "product";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }
    public function index()
    {
        try {

            $params['CustomerCount'] = Customer::where('is_seller', 0)->count();
            $params['SellerCount'] = Customer::where('is_seller', 1)->count();
            $params['SellerRequestCount'] = SellerRequest::where('status', 0)->count();
            $params['DeliveryBoyCount'] = DeliveryBoy::where('verification_status', 1)->count();
            $params['ItemCount'] = Items::where('is_approved', 1)->count();
            $params['OrdersCount'] = Orders::distinct('group_order_id')->count();
            $params['CategoryCount'] = Category::where('parent_category_id', 0)->count();
            $params['BrandCount'] = Brand::count();
            $params['OffersCount'] = Offers::count();
            $params['ProductRequestCount'] = Items::where('is_approved', 0)->count();

            $customer_data = [];
            $customer_month = [];
            $seller_data = [];
            $seller_month = [];
            $deliveryboy_data = [];
            $deliveryboy_month = [];

            $d = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            for ($i = 1; $i < 13; $i++) {
                $customer_data['sum'][] = Customer::where('is_seller', 0)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
                $seller_data['sum'][] = Customer::where('is_seller', 1)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
                $deliveryboy_data['sum'][] = DeliveryBoy::whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
            }

            for ($i = 1; $i <= $d; $i++) {
                $customer_month['sum'][] = Customer::where('is_seller', 0)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->count();
                $seller_month['sum'][] = Customer::where('is_seller', 1)->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->count();
                $deliveryboy_month['sum'][] = DeliveryBoy::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->count();
            }

            $params['customer_year'] = json_encode($customer_data);
            $params['seller_year'] = json_encode($seller_data);
            $params['deliveryboy_year'] = json_encode($deliveryboy_data);
            $params['customer_month'] = json_encode($customer_month);
            $params['seller_month'] = json_encode($seller_month);
            $params['deliveryboy_month'] = json_encode($deliveryboy_month);

            //  Top Seller Items
            $topItems = OrdersItems::join('tbl_orders', function ($join) {
                $join->on('tbl_order_item.group_order_id', '=', DB::raw('tbl_orders.group_order_id COLLATE utf8mb4_general_ci'));
            })
                ->where('tbl_orders.order_status', 6) // Delivered
                ->select('tbl_order_item.item_id', 'tbl_order_item.seller_id')
                ->selectRaw('SUM(tbl_order_item.order_quantity) as total_sold')
                ->groupBy('tbl_order_item.item_id', 'tbl_order_item.seller_id')
                ->orderByDesc('total_sold')
                ->get();

            $sellerIds = $topItems->pluck('seller_id')->unique();
            $topSellers = Customer::whereIn('id', $sellerIds)->take(6)->get();
            $topSellers = $topSellers->map(function ($seller) use ($topItems) {
                $seller->total_sold = $topItems->where('seller_id', $seller->id)->sum('total_sold');
                return $seller;
            });

            $params['top_seller'] = $topSellers;
            $this->common->imageNameToUrl($params['top_seller'], 'image', $this->folder_customer);

            // Top Selling product
            $topItemStats = OrdersItems::join('tbl_orders', function ($join) {
                $join->on('tbl_order_item.group_order_id', '=', DB::raw('tbl_orders.group_order_id COLLATE utf8mb4_general_ci'));
            })
                ->where('tbl_orders.order_status', 6) // Delivered
                ->select('tbl_order_item.item_id', 'tbl_order_item.seller_id')
                ->selectRaw('SUM(tbl_order_item.order_quantity) as total_sold')
                ->selectRaw('COUNT(DISTINCT tbl_orders.customer_id) as total_customers')
                ->groupBy('tbl_order_item.item_id', 'tbl_order_item.seller_id')
                ->orderByDesc('total_sold')
                ->get();

            $itemIds = $topItemStats->pluck('item_id')->unique();
            $topItems = Items::whereIn('id', $itemIds)->take(5)->get();
            $topItems = $topItems->map(function ($item) use ($topItemStats) {
                $stats = $topItemStats->where('item_id', $item->id);
                $item->total_sold = $stats->sum('total_sold');
                $item->total_customers = $stats->sum('total_customers');
                return $item;
            });

            $this->common->imageNameToUrl($topItems, 'portrait_img', $this->folder_product);
            $params['top_item'] = $topItems;

            // Most Used Hashtag
            $params['most_used_hashtag'] = Hashtag::orderBy('total_used', 'desc')->take(8)->get();

            // Top Brands
            $params['top_brand'] = Brand::where('status', 1)
                ->orderBy('id', 'desc')
                ->take(8)
                ->get();
            $this->common->imageNameToUrl($params['top_brand'], 'logo', $this->folder_brand);

            // Top item
            $topItem = Review::select('content_id', DB::raw('AVG(rating) as ratings'))->where('type', 1)->orderby('ratings', 'DESC')->with('item')->where('status', 1)->groupBy('content_id')->get();

            $params['top_rating_item'] = array();
            for ($i = 0; $i < count($topItem); $i++) {
                // dd($topItem[$i]['item']);
                if ($topItem[$i]['item'] != null && isset($topItem[$i]['item'])) {
                    $this->common->imageNameToUrl(array($topItem[$i]['item']), 'portrait_img', $this->folder_product);
                    $params['top_rating_item'][] = $topItem[$i];
                }
                if (count($params['top_rating_item']) == 6) {
                    break;
                }
            }

            //Orders Overview
            $statuses = [
                1 => 'Pending',
                2 => 'Confirmed',
                3 => 'Picking',
                4 => 'Packed',
                5 => 'Out-for-Delivery',
                6 => 'Delivered',
                7 => 'Cancelled',
                8 => 'Rejected',
            ];

            $statusColors = [
                'Pending' => '#f59e0b',
                'Confirmed' => '#3b82f6',
                'Picking' => '#8b5cf6',
                'Packed' => '#10b981',
                'Out-for-Delivery' => '#f43f5e',
                'Delivered' => '#6366f1',
                'Cancelled' => '#ef4444',
                'Rejected' => '#6b7280',
            ];

            $getOrdersData = function ($period) use ($statuses) {
                $data = [];
                $labels = [];

                if ($period == 'week') {
                    $dates = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = date('Y-m-d', strtotime("-$i days"));
                        $dates[] = $date;
                        $labels[] = date('D', strtotime($date));
                    }

                    foreach ($statuses as $key => $status) {
                        $data[$status] = [];
                        foreach ($dates as $date) {
                            $data[$status][] = Orders::where('order_status', $key)
                                ->whereDate('created_at', $date)
                                ->count();
                        }
                    }
                } elseif ($period == 'month') {
                    $labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                    $startOfMonth = now()->startOfMonth();
                    for ($w = 0; $w < 4; $w++) {
                        $start = $startOfMonth->copy()->addWeeks($w);
                        $end = $startOfMonth->copy()->addWeeks($w + 1)->subDay();
                        foreach ($statuses as $key => $status) {
                            $data[$status][$w] = Orders::where('order_status', $key)
                                ->whereBetween('created_at', [$start, $end])
                                ->count();
                        }
                    }
                } elseif ($period == 'year') {
                    for ($m = 1; $m <= 12; $m++) {
                        $labels[] = date('M', mktime(0, 0, 0, $m, 1));
                    }

                    foreach ($statuses as $key => $status) {
                        $data[$status] = [];
                        for ($m = 1; $m <= 12; $m++) {
                            $data[$status][] = Orders::where('order_status', $key)
                                ->whereMonth('created_at', $m)
                                ->count();
                        }
                    }
                }

                return ['labels' => $labels, 'data' => $data];
            };

            $params['statuses'] = $statuses;
            $params['statusColors'] = $statusColors;
            $params['weekData'] = $getOrdersData('week');
            $params['monthData'] = $getOrdersData('month');
            $params['yearData'] = $getOrdersData('year');

            return view('admin.dashboard.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function EarningReport()
    {
        try {
            $year = date('Y');
            $month = date('m');
            $monthName = date('M');

            $yearly = Orders::whereYear('created_at', $year)
                ->where('order_status', 6)
                ->selectRaw('
                SUM(total_payable_amount) as total_revenue,
                SUM(seller_commission) as seller_earned,
                SUM(delivery_boy_commission) as delivery_boy_earned,
                SUM(
                    total_payable_amount 
                    - seller_commission 
                    - delivery_boy_commission
                ) as admin_earned
            ')
                ->first();

            $monthly = Orders::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->where('order_status', 6)
                ->selectRaw('
                    SUM(total_payable_amount) as total_revenue,
                    SUM(seller_commission) as seller_earned,
                    SUM(delivery_boy_commission) as delivery_boy_earned,
                    SUM(
                        total_payable_amount 
                        - seller_commission 
                        - delivery_boy_commission
                    ) as admin_earned
                ')
                ->first();

            $weekStart = date('Y-m-d 00:00:00', strtotime('monday this week'));
            $weekEnd = date('Y-m-d 23:59:59', strtotime('sunday this week'));


            $weekly = Orders::whereBetween('created_at', [$weekStart, $weekEnd])
                ->where('order_status', 6)
                ->selectRaw('
            SUM(total_payable_amount) as total_revenue,
            SUM(seller_commission) as seller_earned,
            SUM(delivery_boy_commission) as delivery_boy_earned,
            SUM(total_payable_amount - seller_commission - delivery_boy_commission) as admin_earned
        ')
                ->first();


            $earnings = Orders::whereYear('created_at', $year)
                ->where('order_status', 6) // Delivered orders only
                ->selectRaw('
                    MONTH(created_at) as month,
                    SUM(seller_commission) as seller_total,
                    SUM(delivery_boy_commission) as delivery_total,
                    SUM(total_payable_amount - seller_commission - delivery_boy_commission) as admin_total
                ')
                ->groupBy('month')
                ->get();

            // Default 12 months 0 value set karo
            $adminData = array_fill(0, 12, 0);
            $sellerData = array_fill(0, 12, 0);
            $deliveryData = array_fill(0, 12, 0);

            foreach ($earnings as $row) {

                $index = $row->month - 1;

                $adminData[$index] = (float) $row->admin_total;
                $sellerData[$index] = (float) $row->seller_total;
                $deliveryData[$index] = (float) $row->delivery_total;
            }

            // Withdrawal Statistice
            $withdrawal_data['sum'][0] = (int) DeliveryWithdrawal::whereYear('created_at', date('Y'))->where('status', 0)->sum('total_amount');
            $withdrawal_data['sum'][1] = (int) DeliveryWithdrawal::whereYear('created_at', date('Y'))->where('status', 1)->sum('total_amount');

            $params = [

                'year' => $year,
                'month' => $monthName,
                'year_totalRevenue' => $yearly->total_revenue ?? 0,
                'year_adminEarned' => $yearly->admin_earned ?? 0,
                'year_sellerEarned' => $yearly->seller_earned ?? 0,
                'year_deliveryBoyEarned' => $yearly->delivery_boy_earned ?? 0,

                'month_totalRevenue' => $monthly->total_revenue ?? 0,
                'month_adminEarned' => $monthly->admin_earned ?? 0,
                'month_sellerEarned' => $monthly->seller_earned ?? 0,
                'month_deliveryBoyEarned' => $monthly->delivery_boy_earned ?? 0,

                'week_totalRevenue' => $weekly->total_revenue ?? 0,
                'week_adminEarned' => $weekly->admin_earned ?? 0,
                'week_sellerEarned' => $weekly->seller_earned ?? 0,
                'week_deliveryBoyEarned' => $weekly->delivery_boy_earned ?? 0,

                'adminData' => $adminData,
                'sellerData' => $sellerData,
                'deliveryData' => $deliveryData,

                'withdrawal_earning' =>  json_encode($withdrawal_data),
            ];

            return view('admin.reports.earning', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function SalesReport(Request $request)
    {
        try {
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->get();
            $seller_id = $request->seller_id;
            $params['seller_id'] = $seller_id ?? 0;

            // Base query
            $order = Orders::query();
            if ($seller_id != 0) {
                $order->where('seller_id', $seller_id);
            }

            // Order Statistic
            $params['daily_sales'] = [];
            $params['weekly_sales'] = [];
            $params['monthly_sales'] = [];
            $params['yearly_sales'] = [];
            $d = date('t', mktime(0, 0, 0, date('m'), 1, date('Y')));


            // Yearly Sales
            for ($i = 1; $i < 13; $i++) {
                $Sum = (clone $order)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $i)
                    ->count();
                $params['yearly_sales']['sum'][] = $Sum;
            }
            // Monthly Sales
            for ($i = 1; $i <= $d; $i++) {
                $Sum = (clone $order)
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', date('m'))
                    ->whereDay('created_at', $i)
                    ->count();
                $params['monthly_sales']['sum'][] = $Sum;
            }
            // Weekly Sales
            $startOfWeek = date('Y-m-d', strtotime('last sunday', strtotime('tomorrow')));
            for ($i = 0; $i < 7; $i++) {
                $date = date('Y-m-d', strtotime("$startOfWeek +$i days"));
                $Sum = (clone $order)
                    ->whereDate('created_at', $date)
                    ->count();

                $params['weekly_sales']['labels'][] = $date;
                $params['weekly_sales']['sum'][] = $Sum;
            }
            // Daily Sales
            for ($i = 0; $i < 24; $i++) {
                $Sum = (clone $order)
                    ->whereDate('created_at', date('Y-m-d'))
                    ->whereRaw('HOUR(created_at) = ?', [$i])
                    ->count();

                $params['daily_sales']['labels'][] = $i;
                $params['daily_sales']['sum'][] = $Sum;
            }

            // Totals by Payment Method
            $totalOrders = (clone $order)->whereYear('created_at', date('Y'))->count();
            $totalCashOrders = (clone $order)->whereYear('created_at', date('Y'))->where('payment_method', 1)->count();
            $totalOnlineOrders = (clone $order)->whereYear('created_at', date('Y'))->where('payment_method', 2)->count();

            $cashPercentage = $totalOrders > 0 ? round(($totalCashOrders / $totalOrders) * 100, 2) : 0;
            $onlinePercentage = $totalOrders > 0 ? round(($totalOnlineOrders / $totalOrders) * 100, 2) : 0;

            $params['total_orders'] = $totalOrders;
            $params['cash_orders'] = $totalCashOrders;
            $params['online_orders'] = $totalOnlineOrders;
            $params['cash_percentage'] = $cashPercentage;
            $params['online_percentage'] = $onlinePercentage;

            if ($request->ajax()) {
                return response()->json($params);
            }

            return view('admin.reports.sales', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function SellerReport(Request $request)
    {
        try {
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->get();
            $seller_id = $request->seller_id;
            $params['seller_id'] = $seller_id ?? 0;

            // Base query
            $order = Orders::query();
            if ($seller_id != 0) {
                $order->where('seller_id', $seller_id);
            }

            $params['total_revenue'] = [];
            $params['total_comission'] = [];

            for ($month = 1; $month <= 12; $month++) {

                $monthlyOrders = (clone $order)
                    ->where('order_status', 6) // delivered
                    ->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $month);

                // Total Revenue
                $totalRevenue = (clone $monthlyOrders)->sum('total_payable_amount');

                // Seller Commission
                $sellerCommission = (clone $monthlyOrders)->sum('seller_commission');

                // Delivery Boy Commission
                $deliveryCommission = (clone $monthlyOrders)->sum('delivery_boy_commission');

                // ✅ Admin Commission Calculate
                $adminCommission = $totalRevenue - $sellerCommission - $deliveryCommission;

                $params['total_revenue'][] = $totalRevenue;
                $params['total_comission'][] = $adminCommission;
            }

            // Initialize
            $params['total_orders'] = [];
            $params['total_cancel_orders'] = [];

            // Total orders
            $totalOrders = (clone $order)->whereYear('created_at', date('Y'))->count();

            // Total cancelled orders
            $totalCancelled = (clone $order)->whereYear('created_at', date('Y'))->where('order_status', 7)->count();

            // Calculate percentages for pie chart
            $cancelPercentage = $totalOrders > 0 ? round(($totalCancelled / $totalOrders) * 100, 2) : 0;
            $totalordersPercentage = $totalOrders > 0 ? 100 - $cancelPercentage : 0;

            $params['total_orders'] = $totalOrders;
            $params['total_cancel_orders'] = $totalCancelled;
            $params['orders_parcebtage'] = $totalordersPercentage;
            $params['cancel_orders_parcebtage'] = $cancelPercentage;

            if ($request->ajax()) {
                return response()->json($params);
            }

            return view('admin.reports.seller', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function topSellingItems(Request $request)
    {
        try {
            $params = [];
            $seller_id = $request->seller_id;

            // Most profitable Items
            $order_items = OrdersItems::query();
            if ($seller_id != 0) {
                $order_items->where('seller_id', $seller_id);
            }

            $data = [];
            $most_profit_items = $order_items->select(
                'item_id',
                DB::raw('SUM(order_quantity) as total_sold'),
                DB::raw('SUM(order_quantity_amount) as total_revenue')
            )
                ->groupBy('item_id')
                ->orderByDesc('total_revenue')
                ->with('item')
                ->get();

            foreach ($most_profit_items as $value) {

                if ($value['item'] != null) {
                    $value['item']['portrait_img'] = $this->common->getImage($this->folder_product, $value['item']['portrait_img']);
                    $data[] = $value;
                }
            }

            return DataTables()::of($data)
                ->addIndexColumn()
                ->make(true);

            return view('admin.reports.sales', $params);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function leastSellingItems(Request $request)
    {
        try {
            $params = [];
            $seller_id = $request->seller_id;

            $order_items = OrdersItems::query();
            if ($seller_id != 0) {
                $order_items->where('seller_id', $seller_id);
            }

            $data = [];
            $most_profit_items = $order_items->select(
                'item_id',
                DB::raw('SUM(order_quantity) as total_sold'),
                DB::raw('SUM(order_quantity_amount) as total_revenue'),
                DB::raw('MAX(item_price) as item_price')

            )
                ->groupBy('item_id')
                ->orderBy('total_sold', 'asc')
                ->with('item')
                ->get();

            foreach ($most_profit_items as $value) {

                if ($value['item'] != null) {
                    $value['item']['portrait_img'] = $this->common->getImage($this->folder_product, $value['item']['portrait_img']);
                    $data[] = $value;
                }
            }

            return DataTables()::of($data)
                ->addIndexColumn()
                ->make(true);

            return view('admin.sales_report.index', $params);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function Page()
    {
        try {
            $currentURL = URL::current();

            $link_array = explode('/', $currentURL);
            $page = end($link_array);

            $params['result'] = Page::where('page_name', $page)->first();
            if (isset($params['result'])) {

                $params['settings'] = Setting_Data();

                return view('page', $params);
            } else {
                return view('errors.404');
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
