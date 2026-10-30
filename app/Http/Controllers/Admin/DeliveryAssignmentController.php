<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\Orders;
use Illuminate\Http\Request;
use Exception;

class DeliveryAssignmentController extends Controller
{

    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['deliveryboy'] = DeliveryBoy::where('status', 1)->where('verification_status', 1)->latest()->get();
            $params['AssignedOrders'] = DeliveryAssignment::where('status', 1)->count();
            $params['PickedUpOrders'] = DeliveryAssignment::where('status', 2)->count();
            $params['OnthewayOrders'] = DeliveryAssignment::where('status', 3)->count();
            $params['DeliveredOrders'] = DeliveryAssignment::where('status', 4)->count();
            $params['CancelledOrders'] = DeliveryAssignment::where('status', 5)->count();
            $params['RejectedOrders'] = DeliveryAssignment::where('status', 6)->count();

            if ($request->ajax()) {
                $input_req_status = $request['input_req_status'];

                $input_search = $request['input_search'];
                $input_delivery_boy_id = $request['input_delivery_boy_id'];
                $input_status = $request['input_status'];

                if ($input_req_status == 0) {
                    $query = DeliveryAssignment::with('deliveryboy', 'group_order');
                } else {
                    $query = DeliveryAssignment::where('status', $input_req_status)->with('deliveryboy', 'group_order');
                }

                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('group_order_id', 'LIKE', "%{$input_search}%")->orWhere('pickup_location', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_delivery_boy_id !== 'all') {
                    $query->where('delivery_boy_id', $input_delivery_boy_id);
                }
                if ($input_status !== 'all') {
                    $query->where('status', $input_status);
                }

                $data = $query->orderBy('id', 'desc')->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<div class="d-flex justify-content-around">';

                        if ($row->status == 1) {

                            // Accept Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal" data-id="' . $row->id . '"data-status="1"><i class="fa-solid fa-check fa-xl"></i>
                                    </a>';

                            // Reject Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-id="' . $row->id . '"data-status="6"><i class="fa-solid fa-xmark fa-xl"></i>
                                    </a>';
                        } else if ($row->status == 2) {
                            // preparing Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-id="' . $row->id . '"data-status="2"><i class="fa-solid fa-truck fa-xl"></i>
                             </a>';
                        } else if ($row->status == 3) {
                            // Reay-for-pickup Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-id="' . $row->id . '"data-status="4"><i class="fa-solid fa-circle-check fa-xl"></i>
                             </a>';
                        } else if ($row->status == 4) {
                            $btn .= '<i class="fa-solid fa-circle-check fa-2xl text-success"></i>';
                        } else if ($row->status == 5) {
                            $btn .= '<i class="fa-solid fa-ban fa-2xl text-secondary"></i>';
                        } else if ($row->status == 6) {
                            $btn .= '<i class="fa-solid fa-circle-xmark fa-2xl text-danger"></i>';
                        }

                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $assign = __('label.assign');
                        $pickup     = __('label.pickup');
                        $on_the_way = __('label.on_the_way');
                        $delivered = __('label.delivered');
                        $cancelled = __('label.cancelled');
                        $rejected = __('label.rejected');

                        $btn = '<div class="d-flex justify-content-center">';

                        switch ($row->status) {

                            case 1:
                                $btn .= '<span class="status-badge badge-secondary">' . $assign . '</span>';
                                break;

                            case 2:
                                $btn .= '<span class="status-badge badge-warning">' . $pickup . '</span>';
                                break;

                            case 3:
                                $btn .= '<span class="status-badge badge-info">' . $on_the_way . '</span>';
                                break;

                            case 4:
                                $btn .= '<span class="status-badge badge-success">' . $delivered . '</span>';
                                break;

                            case 5:
                                $btn .= '<span class="status-badge badge-dark">' . $cancelled . '</span>';
                                break;

                            case 6:
                                $btn .= '<span class="status-badge badge-danger">' . $rejected . '</span>';
                                break;

                            default:
                                $btn .= '<span class="status-badge badge-light">N/A</span>';
                                break;
                        }
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('pickup_details', function ($row) {

                        // Pickup Time
                        $pickupTime = $row->pickup_time
                            ? date('d M h:i A', strtotime($row->pickup_time))
                            : '--';

                        // Pickup Address
                        $pickupAddress = $row->pickup_location
                            ? e($row->pickup_location)
                            : '--';

                        // Estimated Delivery Time (ISO -> readable)
                        $estimatedTime = $row->estimated_delivery_time
                            ? date('d M h:i A', strtotime($row->estimated_delivery_time))
                            : '--';

                        $html  = '<div class="d-flex flex-column text-left">';
                        $html .= '<span><strong>' . __('label.pickup_time') . ' :</strong> ' . $pickupTime . '</span>';
                        $html .= '<span><strong>' . __('label.pickup_location') . ' :</strong> ' . $pickupAddress . '</span>';
                        $html .= '<span><strong>' . __('label.estimated_delivery_time') . ' :</strong> ' . $estimatedTime . '</span>';
                        $html .= '</div>';

                        return $html;
                    })
                    ->rawColumns(['action', 'status', 'pickup_details'])
                    ->make(true);
            }

            return view('admin.delivery_assignment.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show(Request $request) //Status 1-Accepted ,2-Pickup-confirm, 4-Deliverd  ,6- Rejected
    {
        try {
            $status = $request['req_status'];
            $id = $request['id'];

            $assignment = DeliveryAssignment::where('id', $id)->first();
            $order = Orders::where('group_order_id', $assignment['group_order_id'])->with('seller', 'customer')->first();

            $group_order_id = $order['group_order_id'];
            switch ($status) {

                case 1:

                    $assignment->status = 2; // Accepted
                    $assignment->save();
                    break;

                case 2:
                    $assignment->status = 3; // Pickup Confirm
                    $assignment->save();

                    // Update Master Order Status → Out for Delivery
                    $order->order_status = 5;
                    $order->save();

                    $this->common->OrderTrackingAdd($order->group_order_id, 5);

                    break;

                case 4:
                    $assignment->status = 4;
                    $assignment->save();

                    $order->order_status = 6; // Delivered
                    $order->save();

                    $this->common->OrderTrackingAdd($order->group_order_id, 6);
                    $this->common->CreateSettlement($order->group_order_id);

                    break;

                case 6:

                    $assignment->status = 6;
                    $assignment->save();

                    // Order stays packed (4) for reassignment
                    $order->order_status = 4;
                    $order->save();

                    break;
            }

            $customer = $order->customer;
            $seller = $order->seller;

            $array = [
                'status_type' => 'delivery-side',
                'status' => $status,
                'group_order_id' => $order->group_order_id,
                'delivery_otp' => $order->delivery_otp,
                'seller_name' => $seller['user_name'],
                'customer_name' => $customer['user_name'],
            ];
            $Noti = $this->common->BasicNotiConfiguration('order-status-change');
            $array['notification_type'] = $Noti['type'];
            if ($Noti['status'] == 1) {

                switch ($status) {

                    case 1:
                        $array['send_type'] = 'seller';

                        if ($Noti['send_mail'] == 1) {
                            $this->common->Send_Mail($Noti['type'], $seller->email, $array);
                        }
                        if ($Noti['send_notification'] == 1) {
                            $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0, $group_order_id, $seller['device_token'], $seller['device_type'], $array);
                        }

                        break;

                    case 2:

                        $array['send_type'] = 'seller';

                        if ($Noti['send_mail'] == 1) {
                            $this->common->Send_Mail($Noti['type'], $seller->email, $array);
                        }

                        if ($Noti['send_notification'] == 1) {
                            $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0, $group_order_id, $seller['device_token'], $seller['device_type'], $array);
                        }

                        $array['send_type'] = 'customer';

                        if ($Noti['send_mail'] == 1) {
                            $this->common->Send_Mail($Noti['type'], $customer->email, $array);
                        }

                        if ($Noti['send_notification'] == 1) {
                            $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
                        }

                        break;
                    case 4:

                        $array['send_type'] = 'customer';

                        if ($Noti['send_mail'] == 1) {
                            $this->common->Send_Mail($Noti['type'], $customer->email, $array);
                        }

                        if ($Noti['send_notification'] == 1) {
                            $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
                        }

                        break;
                    case 6:

                        $array['send_type'] = 'seller';

                        if ($Noti['send_mail'] == 1) {
                            $this->common->Send_Mail($Noti['type'], $seller->email, $array);
                        }

                        if ($Noti['send_notification'] == 1) {
                            $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0, $group_order_id, $seller['device_token'], $seller['device_type'], $array);
                        }

                        break;
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
