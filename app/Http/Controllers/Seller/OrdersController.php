<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\Items;
use App\Models\Orders;
use App\Models\ReturnRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Exception;


class OrdersController extends Controller
{
    private $folder = "product";
    private $folder_customer = "customer";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $seller = SellerData();

            $params['data'] = [];

            $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();
            $params['item'] = Items::where('seller_id', $seller['id'])->where('status', 1)->latest()->get();
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();

            $params['data'] = [];
            $params['PendingCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 1)->count();
            $params['ConfirmedCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 2)->count();
            $params['ShippedCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 3)->count();
            $params['OutDeliveryCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 4)->count();
            $params['DeliveredCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 5)->count();
            $params['CancelledCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 6)->count();
            $params['ReturnRequestCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 7)->count();
            $params['ReturnInProcessCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 8)->count();
            $params['ReturnedCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 9)->count();
            $params['ReturnedRejectedCount'] = Orders::where('seller_id', $seller['id'])->where('order_status', 10)->count();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_payment_method = $request['input_payment_method'];
                $input_item = $request['input_item_id'];
                $input_customer = $request['input_customer_id'];
                $input_status = $request['input_status'];

                $query = Orders::where('seller_id', $seller['id'])->with('customer', 'customer_address', 'seller', 'item');

                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('group_order_id', 'LIKE', "%{$input_search}%")->orWhere('order_number', 'LIKE', "%{$input_search}%")->orWhere('order_notes', 'LIKE', "%{$input_search}%");
                    });
                }

                if ($input_payment_method !== 'all') {
                    $query->where('payment_method', $input_payment_method);
                }
                if ($input_item !== 'all') {
                    $query->where('item_id', $input_item);
                }
                if ($input_customer !== 'all') {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_status !== 'all') {
                    $query->where('order_status', $input_status);
                }

                $data = $query->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        // Start building the buttons
                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('seller.order.view', [$row->order_number]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-eye fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('group_action', function ($row) {
                        $view_orders = __('label.view_orders');
                        $view_order = __('label.view_order');
                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('seller.order.edit', [$row->group_order_id]) . '" title="' . $view_orders . '">';
                        $btn .= '<span class="order-badge order-badge-info mr-1">' . $view_order . '</span>';
                        $btn .= '</a>';

                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('paid_amount', function ($row) {
                        $params['total_amount'] = Orders::where('order_number', $row['order_number'])->pluck('item_amount')->first();
                        $params['delivery_charges'] = Orders::where('order_number', $row['order_number'])->pluck('delivery_charges')->first();
                        $params['tax_amount'] = Orders::where('order_number', $row['order_number'])->pluck('tax_amount')->first();
                        $params['discount_amount'] = Orders::where('order_number', $row['order_number'])->pluck('discount_amount')->first();

                        $final_total = $params['total_amount'] + $params['tax_amount'] - $params['discount_amount'];

                        return $final_total;
                    })
                    ->addColumn('status', function ($row) {
                        $pending = __('label.pending');
                        $confirmed = __('label.confirmed');
                        $shipped = __('label.shipped');
                        $out_for_delivery = __('label.out_for_delivery');
                        $delivered = __('label.delivered');
                        $cancelled = __('label.cancelled');
                        $return_requested = __('label.return_requested');
                        $return_inprocess = __('label.return_inprocess');
                        $returned = __('label.returned');
                        $returnedrejected = __('label.returnedrejected');
                        $btn = '<div class="d-flex justify-content-around">';

                        if ($row->order_status == 1) {
                            $btn .= '<span class="order-badge order-badge-gray mr-1">' . $pending . '</span>';
                        } else if ($row->order_status == 2) {
                            $btn .= '<span class="order-badge order-badge-blue mr-1">' . $confirmed . '</span>';
                        } else if ($row->order_status == 3) {
                            $btn .= '<span class="order-badge order-badge-orange mr-1">' . $shipped . '</span>';
                        } else if ($row->order_status == 4) {
                            $btn .= '<span class="order-badge order-badge-purple mr-1">' . $out_for_delivery . '</span>';
                        } else if ($row->order_status == 5) {
                            $btn .= '<span class="order-badge order-badge-green mr-1">' . $delivered . '</span>';
                        } else if ($row->order_status == 6) {
                            $btn .= '<span class="order-badge order-badge-red mr-1">' . $cancelled . '</span>';
                        } else if ($row->order_status == 7) {
                            $btn .= '<span class="order-badge order-badge-yellow mr-1">' . $return_requested . '</span>';
                        } else if ($row->order_status == 8) {
                            $btn .= '<span class="order-badge order-badge-light-blue mr-1">' . $return_inprocess . '</span>';
                        } else if ($row->order_status == 9) {
                            $btn .= '<span class="order-badge order-badge-dark-green mr-1">' . $returned . '</span>';
                        } else if ($row->order_status == 10) {
                            $btn .= '<span class="order-badge order-badge-dark-orange mr-1">' . $returnedrejected . '</span>';
                        }

                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'status', 'paid_amount', 'group_action'])
                    ->make(true);
            }

            return view('seller.orders.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($group_order_id)
    {
        try {
            $seller = SellerData();
            $params['group_order_no'] = $group_order_id;


            $params['orders'] = Orders::where('seller_id', $seller['id'])->where('group_order_id', $group_order_id)->with('customer', 'seller', 'item')->get();

            foreach ($params['orders'] as $value) {
                $value->item->portrait_img = $this->common->getImage($this->folder, $value->item->portrait_img);
            }

            $params['data'] = Orders::where('group_order_id', $group_order_id)->with('customer', 'item')->first();
            //Order Date 
            $params['data']['order_date'] =  $this->common->GetDateTime($params['data']['updated_at']);
            //Tracking Order Date 
            $params['data']['tracking_order_date'] =  $this->common->GetDateTime($params['data']['updated_at']);
            //Customer Img & Shipping Address
            $customerImage = $params['data']->customer;
            $this->common->imageNameToUrl(array($customerImage), 'image', $this->folder_customer);
            $params['customer_info'] = CustomerAddress::where('id', $params['data']['customer_address_id'])->first();

            $params['total_amount'] = Orders::where('seller_id', $seller['id'])
                ->where('group_order_id', $group_order_id)
                ->sum('item_amount');

            $params['delivery_charges'] = Orders::where('seller_id', $seller['id'])
                ->where('group_order_id', $group_order_id)
                ->sum('delivery_charges');

            $params['tax_amount'] = Orders::where('seller_id', $seller['id'])
                ->where('group_order_id', $group_order_id)
                ->sum('tax_amount');

            $params['discount_amount'] = Orders::where('seller_id', $seller['id'])
                ->where('group_order_id', $group_order_id)
                ->sum('discount_amount');

            // Final total = item + tax + delivery - discount
            $params['final_total'] = round(
                $params['total_amount'] + $params['tax_amount'] + $params['delivery_charges'] - $params['discount_amount'],
                2
            );

            return view('seller.orders.edit', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ViewOrder($order_no)
    {

        try {
            $params['order_no'] = $order_no;
            $params['data'] = Orders::where('order_number', $order_no)->with('customer', 'seller', 'customer_address')->first();
            $itemImage = $params['data']->item;
            $this->common->imageNameToUrl(array($itemImage), 'portrait_img', $this->folder);
            //Order Date 
            $params['data']['order_date'] =  $this->common->GetDateTime($params['data']['created_at']);
            //Tracking Order Date 
            $params['data']['tracking_order_date'] =  $this->common->GetDateTime($params['data']['updated_at']);
            //Customer Img & Shipping Address
            $customerImage = $params['data']->customer;
            $this->common->imageNameToUrl(array($customerImage), 'image', $this->folder_customer);

            $sellerImage = $params['data']->seller;
            $this->common->imageNameToUrl(array($sellerImage), 'image', $this->folder_customer);

            $params['total_amount'] = Orders::where('order_number', $order_no)->pluck('item_amount')->first();
            $params['delivery_charges'] = Orders::where('order_number', $order_no)->pluck('delivery_charges')->first();
            $params['tax_amount'] = Orders::where('order_number', $order_no)->pluck('tax_amount')->first();
            $params['discount_amount'] = Orders::where('order_number', $order_no)->pluck('discount_amount')->first();

            $params['final_total'] = round(
                $params['total_amount'] + $params['tax_amount'] + $params['delivery_charges'] - $params['discount_amount'],
                2
            );

            // $params['total_amount'] = Orders::where('order_number', $order_no)->sum('total_amount');
            // $params['delivery_charges'] = Orders::where('order_number', $order_no)->sum('delivery_charges');
            // $params['tax_amount'] = Orders::where('order_number', $order_no)->sum('tax_amount');
            // $params['discount_amount'] = Orders::where('order_number', $order_no)->sum('discount_amount');
            // $params['final_total'] = $params['total_amount'] + $params['delivery_charges'] + $params['tax_amount'] - $params['discount_amount'];

            return view('seller.orders.view_order', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function OrderChangeStatus($order_id, Request $request)
    {
        try {

            $Noti = $this->common->BasicNotiConfiguration(10);
            $status = $request->order_status;

            $order = Orders::where('group_order_id', $order_id)->with('customer')->first();
            $customer = $order->customer;
            $assign = DeliveryAssignment::where('order_id', $order_id)->first();

            if ($status == 2) {

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 2, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Order Confirmed";
                    $message = "Your order has been confirmed. We will notify you once it is shipped.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } elseif ($status == 3) {
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 3, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Order Shipped";
                    $message = "Good news! Your order has been shipped. It’s on the way.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 4) {
                DeliveryAssignment::where('order_id', $order_id)->update(['delivery_status' => 3]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 4, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Out for Delivery";
                    $message = "Your order is out for delivery. Please be available to receive it.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } elseif ($status == 5) {
                $array = [
                    'vehicle_id' => 0,
                    'status' => 4,
                ];
                DeliveryAssignment::where('order_id', $order_id)->update($array);
                DeliveryBoy::where('id', $assign['delivery_boy_id'])->update(['status' => 1]);
                Vehicle::where('id', $assign['vehicle_id'])->update(['is_used' => 0]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 5, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Order Delivered";
                    $message = "Your order has been delivered successfully. We hope you enjoy it!";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } elseif ($status == 6) {
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 6, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Order Cancelled";
                    $message = "Your order has been cancelled. Please contact support if needed.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 8) {
                DeliveryAssignment::where('order_id', $order_id)->update(['delivery_status' => 7]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 8, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Return in Process";
                    $message = "Your return is being processed. We will notify you with further steps.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 9) {
                DeliveryAssignment::where('order_id', $order_id)->update(['delivery_status' => 8]);
                DeliveryBoy::where('id', $assign['delivery_boy_id'])->update(['status' => 1]);
                Vehicle::where('id', $assign['vehicle_id'])->update(['is_used' => 0]);
                ReturnRequest::where('order_id', $order_id)->update(['order_status' => 3]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 9, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $title = "Order Returned";
                    $message = "Your order has been successfully returned.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            } else if ($status == 10) {
                ReturnRequest::where('order_id', $order_id)->update(['order_status' => 2]);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $Data = $this->common->UpdateOrdersDetails($customer['id'], $order['group_order_id'], $status);
                    $this->common->OtherSendMail(10, 10, $Data);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Return Rejected";
                    $message = "Your return request has been rejected. Please contact support for help.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $order_id, "", "", $customer['device_token'], $customer['device_type']);
                }
            }

            Orders::where('group_order_id', $order_id)->update(['order_status' => $status]);

            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
