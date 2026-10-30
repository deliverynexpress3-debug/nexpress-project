<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\DeliveryAssignment;
use App\Models\Items;
use App\Models\Orders;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;
use Exception;


class OrderReturnRequestController extends Controller
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
            $params['order'] = Orders::latest()->get();
            $params['PendingCount'] = ReturnRequest::where('status', 0)->count();
            $params['ApprovedCount'] = ReturnRequest::where('status', 1)->count();
            $params['RejectedCount'] = ReturnRequest::where('status', 2)->count();
            $params['PickedCount'] = ReturnRequest::where('status', 3)->count();
            $params['CompletedCount'] = ReturnRequest::where('status', 4)->count();

            if ($request->ajax()) {

                $input_order = $request['input_order_id'];
                $input_item = $request['input_item_id'];
                $input_customer = $request['input_customer_id'];
                $input_status = $request['input_status'];

                $query = ReturnRequest::with('order', 'customer', 'item', 'return_reason');

                if ($input_order !== 'all') {
                    $query->where('id', $input_order);
                }
                if ($input_item !== 'all') {
                    $query->where('item_id', $input_item);
                }
                if ($input_customer !== 'all') {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_status !== 'all') {
                    $query->where('status', $input_status);
                }

                $data = $query->latest()->get();
                foreach ($data as $value) {
                    $itemImage = $value->item;
                    $this->common->imageNameToUrl(array($itemImage), 'portrait_img', $this->folder);
                }

                return DataTables()::of($data)
                    ->addIndexColumn()

                    ->addColumn('orders', function ($row) {
                        $orderId = $row->order ? $row->order->group_order_id : '-';
                        $orderNo = $row->order ? $row->order->order_number : '-';
                        return '
                            <div style="text-align: left;">
                                <span style="font-size: 14px; font-weight: 600; color: #333;">' . __('label.order_id') . ':</span> ' . $orderId . '<br>
                                <span style="font-size: 14px; font-weight: 600; color: #333;">' . __('label.order_no') . ':</span> ' . $orderNo . '<br>
                                
                            </div>
                        ';
                    })
                    ->addColumn('paid_amount', function ($row) {
                        $order = Orders::where('id', $row['order_id'])->first();
                        $final_total = round(
                            $order['item_amount'] + $order['tax_amount'] + $order['delivery_charges'] - $order['discount_amount'],
                            2
                        );
                        return $final_total;
                    })

                    ->addColumn('status', function ($row) {
                        $optionsHtml = "<select onchange='change_status(\"$row->order_id\", this.value)' class='form-control'>";
                        $optionsHtml .= "<option value='0'" . ($row->status == 0 ? ' selected' : '') . ">" . __('label.pending') . "</option>";
                        $optionsHtml .= "<option value='1'" . ($row->status == 1 ? ' selected' : '') . ">" . __('label.approved') . "</option>";
                        $optionsHtml .= "<option value='2'" . ($row->status == 2 ? ' selected' : '') . ">" . __('label.rejected') . "</option>";
                        $optionsHtml .= "<option value='3'" . ($row->status == 3 ? ' selected' : '') . ">" . __('label.picked') . "</option>";
                        $optionsHtml .= "<option value='4'" . ($row->status == 4 ? ' selected' : '') . ">" . __('label.completed') . "</option>";
                        $optionsHtml .= "</select>";
                        return $optionsHtml;
                    })

                    ->rawColumns(['action', 'orders', 'status'])
                    ->make(true);
            }


            return view('admin.return_request.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function show($order_id, Request $request)
    {
        try {
            $order = Orders::where('id', $order_id)->with('customer')->first();

            $Noti = $this->common->BasicNotiConfiguration(10);
            $status = $request->status;
            if ($status == 1) {
                $customer = $order->customer;
                Orders::where('id', $order_id)->update(['order_status' => 7]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $this->common->Send_Mail(13, $customer['email']);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Your Return Request Has Been Approved!";
                    $message = "Your return request for the order has been successfully approved. Our delivery team will arrange pickup shortly. Thank you for your patience!";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 2) {
                Orders::where('id', $order_id)->update(['order_status' => 10]);

                $customer = $order->customer;
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $this->common->Send_Mail(14, $customer['email']);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Return Request Rejected";
                    $message = "We’re sorry! Your return request has been rejected after review. For more details, please check your order history or contact support.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 3) {
                DeliveryAssignment::where('order_id', $order_id)->update(['delivery_status' => 6]);
            } else if ($status == 4) {
                DeliveryAssignment::where('order_id', $order_id)->update(['delivery_status' => 8]);
                Orders::where('id', $order_id)->update(['order_status' => 9]);
            }

            ReturnRequest::where('order_id', $order_id)->update(['status' => $status]);
            return response()->json(['status' => 200, 'success' => __('label.status_changed')]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
