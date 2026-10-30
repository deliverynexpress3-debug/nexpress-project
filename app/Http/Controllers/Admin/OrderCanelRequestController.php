<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\OrderCancel;
use App\Models\Orders;
use App\Models\Reason;
use Illuminate\Http\Request;
use Exception;


class OrderCanelRequestController extends Controller
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
            $params['order'] = Orders::latest()->get();
            $params['reason'] = Reason::where('type', 2)->where('status', 1)->latest()->get();

            if ($request->ajax()) {

                $input_order = $request['input_order_id'];
                $input_customer = $request['input_customer_id'];
                $input_reason = $request['input_reason_id'];
                $input_date = $request['input_date'];

                $query = OrderCancel::where('status', 0)->with('order', 'customer', 'reason');

                if ($input_order !== 'all') {
                    $query->where('id', $input_order);
                }
                if ($input_customer !== 'all') {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_reason !== 'all') {
                    $query->where('reason_id', $input_reason);
                }
                if ($input_date != null && isset($input_date)) {
                    $query->whereDate('created_at',  $input_date);
                }

                $data = $query->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()

                    ->addColumn('action', function ($row) {
                        $approved = __('label.approved');
                        $rejected = __('label.rejected');

                        $btn = '<div>';
                        $btn .= '<div class="mb-2">';
                        $btn .= '<a class="openModal" style="cursor: pointer" title="' . $approved . '" data-toggle="modal" data-target="#decisionModal" data-id="' . $row->id . '" data-action="approved">';
                        $btn .= '<span class="badge badge-success">' . $approved . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        // Rejected button
                        $btn .= '<div>';
                        $btn .= '<a class="openModal" style="cursor: pointer" title="' . $rejected . '" data-toggle="modal" data-target="#decisionModal" data-id="' . $row->id . '" data-action="rejected">';
                        $btn .= '<span class="badge badge-danger">' . $rejected . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        return $btn;
                    })
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
                    ->addColumn('product', function ($row) {
                        $order = Orders::where('id', $row->order_id)->first();
                        $item = Items::where('id', $order['item_id'])->first();
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
                        $final_total = round(
                            $row->order->item_amount + $row->order->tax_amount + $row->order->delivery_charges - $row->order->discount_amount,
                            2
                        );
                        return $final_total;
                    })
                    
                    ->rawColumns(['action', 'orders', 'product'])
                    ->make(true);
            }

            return view('admin.cancel_request.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function show($order_id, Request $request)
    {
        try {
            $order = Orders::where('id', $order_id)->with('customer')->first();
            $array = [
                'orders_id' => $order['order_number'],
            ];

            $Noti = $this->common->BasicNotiConfiguration(10);
            $status = $request->status;
            if ($status == 1) {
                $customer = $order->customer;
                Orders::where('id', $order_id)->update(['order_status' => 6]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {

                    $this->common->Send_Mail(18, $customer['email'], $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Order Cancellation Approved!";
                    $message = "Your Order No" . $order['order_number'] . "has been successfully cancelled. We hope to serve you again soon!";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order['order_number'], "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 0) {

                $customer = $order->customer;
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $this->common->Send_Mail(19, $customer['email'], $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "New Order Cancellation Rejected";
                    $message = "Sorry, your cancellation request for Order No:-" . $array['orders_id'] . " " . "has been rejected. Please contact support for more information.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order['order_number'], "", "", $customer['device_token'], $customer['device_type']);
                }
            }

            OrderCancel::where('order_id', $order_id)->update(['status' => $status]);
            return response()->json(['status' => 200, 'success' => __('label.status_changed')]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
