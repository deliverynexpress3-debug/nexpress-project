<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Orders;
use App\Models\OrdersItems;
use Illuminate\Http\Request;
use Exception;

class ItemsSalesReportController extends Controller
{
    private $folder_product = "product";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['item'] = Items::where('is_approved', 1)->latest()->get();
            $params['customer'] = Customer::where('is_seller', 0)->latest()->get();
            // Year
            $params['year_sum'] = Orders::where('order_status', 6)->selectRaw('SUM(total_payable_amount) as total_earning, SUM(seller_commission) as seller_earning, SUM(delivery_boy_commission) as deliveryboy_earning')->first();
            // Month
            $params['month_sum'] = Orders::where('order_status', 6)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->selectRaw('SUM(total_payable_amount) as total_earning, SUM(seller_commission) as seller_earning, SUM(delivery_boy_commission) as deliveryboy_earning')->first();
            // Today
            $params['today_sum'] = Orders::where('order_status', 6)->whereDate('created_at', date('Y-m-d'))->selectRaw('SUM(total_payable_amount) as total_earning, SUM(seller_commission) as seller_earning, SUM(delivery_boy_commission) as deliveryboy_earning')->first();


            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_customer = $request['input_customer'];
                $input_item = $request['input_item'];

                $orders = Orders::where('order_status', 6)
                    ->select(
                        'group_order_id',
                        'seller_commission',
                        'delivery_boy_commission'
                    )
                    ->get()

                    ->keyBy('group_order_id');

                $query = OrdersItems::with(['item', 'customer', 'seller'])

                    ->whereIn(
                        'group_order_id',
                        $orders->keys()
                    );

                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('group_order_id', 'LIKE', "%{$input_search}%")->orWhere('order_number', 'LIKE', "%{$input_search}%")->orWhere('item_price', 'LIKE', "%{$input_search}%")->orWhere('order_quantity_amount', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_customer != "all") {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_item != "all") {
                    $query->where('item_id', $input_item);
                }
                $data = $query->latest()->get();
                $data = $data->map(function ($item) use ($orders) {

                    $order = $orders->get(
                        $item->group_order_id
                    );
                    $item->seller_earning =
                        $order->seller_commission ?? 0;
                    $item->deliveryboy_earning =
                        $order->delivery_boy_commission ?? 0;
                    $itemImage = $item->item ? $item->item->portrait_img : '';
                    $item->item_image_path = $this->common->getImage($this->folder_product, $itemImage);
                    return $item;
                });

                return DataTables()::of($data)
                    ->make(true);
            }
            return view('admin.item_sales_report.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
