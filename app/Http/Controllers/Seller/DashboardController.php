<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Offers;
use App\Models\OrderCancel;
use App\Models\Orders;
use App\Models\Promocode;
use App\Models\ReturnRequest;
use App\Models\Review;
use Exception;

class DashboardController extends Controller
{

    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {
            $seller = SellerData();

            $array = [];
            $order = Orders::where('seller_id', $seller['id'])->get();
            foreach ($order as $value) {
                $array[] = $value['id'];
            }
            $params = [];
            $params['ProductCount'] = Items::count();
            $params['OrdersCount'] = Orders::count();
            $params['ReturnOrderCount'] = ReturnRequest::count();
            $params['CancelOrderCount'] = OrderCancel::count();
            $params['ReviewCount'] = Review::where('type', 2)->count();
            $params['CategoryCount'] = Category::count();
            $params['BrandCount'] = Brand::count();
            $params['OffersCount'] = Offers::where('seller_id', $seller['id'])->count();
            $params['PromocodeCount'] = Promocode::where('seller_id', $seller['id'])->count();
            $params['WalletAmount'] = Customer::where('id', $seller['id'])->sum('wallet_amount');

            $params['todayorders'] = Orders::where('seller_id', $seller['id'])->whereDate('created_at', date('Y-m-d'))->count();
            $params['sales_data'] = [
                'month' => [],
                'count' => [],

            ];
            for ($i = 1; $i <= 12; $i++) {
                // Month name (e.g., Jan, Feb, etc.)
                $monthName = date("M", mktime(0, 0, 0, $i, 1));

                // Get sales count
                $salesCount = Orders::where('seller_id', $seller['id'])->whereYear('created_at', date('Y'))
                    ->whereMonth('created_at', $i)
                    ->count();

                // Store data
                $params['sales_data']['month'][] = $monthName;
                $params['sales_data']['count'][] = $salesCount;
            }

            $currentYear = date('Y');
            $years = [
                $currentYear - 2,
                $currentYear - 1,
                $currentYear
            ];

            $params['yearsData'] = [
                'years' => [],
                'counts' => [],

            ];
            foreach ($years as $year) {
                $count = Orders::where('seller_id', $seller['id'])->whereYear('created_at', $year)->count();
                $params['yearsData']['years'][] = $year;
                $params['yearsData']['counts'][] = $count;
            }

            return view('seller.dashboard.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
