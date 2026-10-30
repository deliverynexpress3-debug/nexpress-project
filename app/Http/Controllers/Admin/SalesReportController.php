<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Orders;
use Illuminate\Http\Request;
use Exception;

class SalesReportController extends Controller
{
    private $folder = "product";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();
            $params['item'] = Items::where('status', 1)->latest()->get();
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();

            // Year
            $params['year_sum'] = Orders::where('order_status', 5)->selectRaw('SUM((item_amount + tax_amount) - discount_amount) as total_earning')->first();
            // Month
            $params['month_sum'] = Orders::where('order_status', 5)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->selectRaw('SUM((item_amount + tax_amount) - discount_amount) as total_earning')->first();
            // Today
            $params['today_sum'] = Orders::where('order_status', 5)->whereDate('created_at', date('Y-m-d'))->selectRaw('SUM((item_amount + tax_amount) - discount_amount) as total_earning')->first();

            if ($request->ajax()) {

                $input_item = $request['input_item_id'];
                $input_seller = $request['input_seller_id'];
                $input_customer = $request['input_customer_id'];
                $input_status = $request['input_status'];

                $query = Orders::where('order_status', 5)->with('customer', 'seller', 'item');

                if ($input_item !== 'all') {
                    $query->where('item_id', $input_item);
                }
                if ($input_seller !== 'all') {
                    $query->where('seller_id', $input_seller);
                }
                if ($input_customer !== 'all') {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_status !== 'all') {
                    $query->where('payment_method', $input_status);
                }

                $data = $query->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('orders', function ($row) {

                        $orderNo = $row->order_number ? $row->order_number : '-';
                        return '
                            <div style="text-align: left;">
                                <span style="font-size: 14px; font-weight: 600; color: #333;">' . __('label.order_no') . ':</span> ' . $orderNo . '<br>
                            </div>
                        ';
                    })
                    ->addColumn('product', function ($row) {
                        $item = $row->item;
                        $this->common->imageNameToUrl([$item], 'portrait_img', $this->folder);

                        return '
                            <div class="d-flex align-items-center">
                                <a href="' . $item['portrait_img'] . '" target="_blank">
                                    <img src="' . $item['portrait_img'] . '" class="img-thumbnail" style="height:55px; width:60px; object-fit:cover; border-radius: 8px;">
                                </a>
                                <span class="ml-3 font-weight-bold" style="white-space: nowrap;">' . $item['name'] . '</span>
                            </div>
                        ';
                    })
                    ->addColumn('paid_amount', function ($row) {
                        $params['total_amount'] = Orders::where('order_number', $row['order_number'])->pluck('item_amount')->first();
                        $params['delivery_charges'] = Orders::where('order_number', $row['order_number'])->pluck('delivery_charges')->first();
                        $params['tax_amount'] = Orders::where('order_number', $row['order_number'])->pluck('tax_amount')->first();
                        $params['discount_amount'] = Orders::where('order_number', $row['order_number'])->pluck('discount_amount')->first();

                        $final_total = Currency_Code() . $params['total_amount'] + $params['tax_amount'] - $params['discount_amount'];

                        return $final_total;
                    })
                    ->rawColumns(['orders', 'paid_amount', 'product'])
                    ->make(true);
            }
            return view('admin.sales_report.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
