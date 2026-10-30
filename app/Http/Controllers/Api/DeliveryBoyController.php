<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\DeliveryWithdrawal;
use App\Models\Notification;
use App\Models\NotificationTracking;
use App\Models\Orders;
use App\Models\Settelment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Exception;

// Login Type : 1- OTP, 2- Goggle, 3- Apple, 4- Normal
class DeliveryBoyController extends Controller
{
    private $folder_deliveryboy = "delivery_boy";
    public $common;
    public $page_limit;
    public function __construct()
    {
        try {
            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT') ?: 10;
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong. Please try again.',
                'errors' => 'Something went wrong. Please try again.',
                'result' => [],
            ]);
        }
    }

    public function Login(Request $request)
    {
        try {

            if ($request['type'] == 1) {

                $validation = Validator::make(
                    $request->all(),
                    [

                        'country_code' => 'required',
                        'mobile_number' => 'required',
                        'country_name' => 'required',
                    ],

                );
            } elseif ($request['type'] == 2) {

                $validation = Validator::make(
                    $request->all(),
                    [

                        'email' => 'required',
                    ],

                );
            } elseif ($request['type'] == 4) {

                $validation = Validator::make(
                    $request->all(),
                    [

                        'email' => 'required|email',
                        'password' => 'required|min:4',
                    ],

                );
            } else {
                $validation = Validator::make(
                    $request->all(),
                    [
                        'type' => 'required|numeric',
                    ],
                    [
                        'type.required' => __('api_msg.type_is_required'),
                    ]
                );
            }
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $image = '';
            if (isset($request['image']) && $request['image'] != null) {
                $file = $request->file('image');
                $image = $this->common->saveImage($file, $this->folder_deliveryboy, 'deliveryboy_');
            }

            $type = $request->type;
            $full_name = isset($request->full_name) ? $request->full_name : '';
            $email = isset($request->email) ? $request->email : '';
            $password = isset($request->password) ? Hash::make($request->password) : '';
            $country_code = isset($request->country_code) ? $request->country_code : '';
            $mobile_number = isset($request->mobile_number) ? $request->mobile_number : '';
            $country_name = isset($request->country_name) ? $request->country_name : '';
            $device_type = isset($request->device_type) ? (int)$request->device_type : 0;
            $device_token = isset($request->device_token) ? $request->device_token : '';


            // OTP
            if ($type == 1) {

                $deliveryboy = DeliveryBoy::where('mobile_number', $mobile_number)->where('type', $type)->latest()->first();
                if (isset($deliveryboy) && $deliveryboy != null) {

                    // Update Device Token && Type
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_token' => $device_token]);
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_type' => $device_type]);

                    $deliveryboy['device_type'] = $device_type;
                    $deliveryboy['device_token'] = $device_token;

                    $this->common->imageNameToUrl(array($deliveryboy), 'image', $this->folder_deliveryboy);
                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($deliveryboy));
                } else {

                    $insert = array(
                        'user_name' => $this->common->delivery_boy_user_name($mobile_number),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => $password,
                        'country_code' => $country_code,
                        'mobile_number' => $mobile_number,
                        'country_name' => $country_name,
                        'image' => $image,
                        'type' => $type,
                        'device_type' => $device_type,
                        'device_token' => $device_token,
                        'status' => 0,
                        'verification_status' => 0,
                    );
                    $deliveryboyId = DeliveryBoy::insertGetId($insert);

                    if (isset($deliveryboyId)) {

                        $deliveryboy = DeliveryBoy::where('id', $deliveryboyId)->first();
                        if (isset($deliveryboy)) {

                            $this->common->imageNameToUrl(array($deliveryboy), 'image', $this->folder_deliveryboy);
                            return $this->common->API_Response(200, __('api_msg.login_successfully'), array($deliveryboy));
                        } else {
                            return $this->common->API_Response(400, __('label.data_not_save'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('label.data_not_save'));
                    }
                }
            }
            // Gmail
            if ($type == 2) {

                $deliveryboy = DeliveryBoy::where('email', $email)->latest()->first();

                if (isset($deliveryboy) && $deliveryboy != null) {

                    // Update Device Token && Type
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_token' => $device_token]);
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_type' => $device_type]);

                    $deliveryboy['device_type'] = $device_type;
                    $deliveryboy['device_token'] = $device_token;

                    $this->common->imageNameToUrl(array($deliveryboy), 'image', $this->folder_deliveryboy);

                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($deliveryboy));
                } else {

                    $email_array = explode('@', $request->email);
                    $insert = array(
                        'user_name' => $this->common->delivery_boy_user_name($email_array[0]),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => "",
                        'country_code' => "",
                        'mobile_number' => "",
                        'country_name' => "",
                        'image' =>  $this->common->generateLetterAvatar($email_array[0], $this->folder_deliveryboy, 'deliveryboy_'),
                        'device_type' => $device_type,
                        'device_token' => $device_token,
                        'type' => $type,
                        'status' => 0,
                        'verification_status' => 0,
                    );
                    $deliveryboyId = DeliveryBoy::insertGetId($insert);

                    if (isset($deliveryboyId)) {

                        $deliveryboy = DeliveryBoy::where('id', $deliveryboyId)->first();
                        $this->common->imageNameToUrl(array($deliveryboy), 'image', $this->folder_deliveryboy);

                        // // Send Mail
                        if ($type == 2) {
                            $NotiStatus = $this->common->BasicNotiConfiguration('login');
                            if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_mail'] == 1) {
                                $this->common->Send_Mail($NotiStatus['type'], $deliveryboy['email']);
                            }
                        }

                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($deliveryboy));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_save'));
                    }
                }
            }
            // Normal
            if ($type == 4) {

                $deliveryboy = DeliveryBoy::where('email', $email)->latest()->first();
                if (isset($deliveryboy)) {

                    // Update Device Token && Type
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_token' => $device_token]);
                    DeliveryBoy::where('id', $deliveryboy['id'])->update(['device_type' => $device_type]);

                    $deliveryboy['device_type'] = $device_type;
                    $deliveryboy['device_token'] = $device_token;

                    if (Hash::check($request['password'], $deliveryboy['password'])) {

                        $this->common->imageNameToUrl(array($deliveryboy), 'image', $this->folder_deliveryboy);

                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($deliveryboy));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.email_pass_worng'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.email_pass_worng'));
                }
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetProfile(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $delivery_boy_id = $request['delivery_boy_id'];

            $data = DeliveryBoy::where('id', $delivery_boy_id)->first();
            if (!empty($data) && isset($data) && $data != null) {

                // Image Name to URL
                $this->common->imageNameToUrl(array($data), 'image', $this->folder_deliveryboy);

                $earningDetails = $this->common->DeliveryBoyEarningDetails($delivery_boy_id);
                $data['complete_orders']    = (int) ($earningDetails['complete_orders']    ?? 0);
                $data['total_earning']      = (int) ($earningDetails['total_earning']      ?? 0);
                $data['today_earning']      = (int) ($earningDetails['today_earning']      ?? 0);
                $data['pending_settlement'] = (int) ($earningDetails['pending_settlement'] ?? 0);
                $data['total_settlement']   = (int) ($earningDetails['total_settlement']   ?? 0);
                $data['next_payout_date']    = $earningDetails['next_payout_date'];

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeleteAccount(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                    'status'    => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $delivery_boy_id = $request['delivery_boy_id'];
            $status = $request['status'];

            $boy = DeliveryBoy::where('id', $delivery_boy_id)->first();

            if ($boy) {

                $boy->status = $status;
                $boy->save();

                //Send Mail & Send Noti
                $array = [
                    'type'  => 'delivery-side',
                    'status' => $status,
                ];
                $NotiStatus = $this->common->BasicNotiConfiguration('account-delete');
                if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {
                    $this->common->SaveNotification('deliveryboy', $NotiStatus['type'], 0, 0, $boy['id'], "", $boy['device_token'], $boy['device_type'], $array);
                }

                return $this->common->API_Response(200, __('api_msg.status_changes'), array($boy));
            } else {
                return $this->common->API_Response(400, __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetOrdersList(Request $request) //Status - 1-Assigned Order ,2-Picked Up, 3-On-the-way ,4-Delivered ,5- Cancelled ,6- Rejected
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                    'status' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $delivery_boy_id = $request['delivery_boy_id'];
            $status = $request['status'];

            if ($status == 0) {
                $data = DeliveryAssignment::where('delivery_boy_id', $delivery_boy_id)->with('group_order', 'deliveryboy');
            } else {
                $data = DeliveryAssignment::where('delivery_boy_id', $delivery_boy_id)->where('status', $status)->with('group_order', 'deliveryboy');
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            $finalData = [];
            foreach ($data as $row) {
                if (!$row->group_order) {
                    continue;
                }
                $finalData[] = $this->common->GetOrdersData($row);
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $finalData, $pagination);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function OrdersDetails(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $group_order_id = $request['group_order_id'];

            $data = DeliveryAssignment::where('group_order_id', $group_order_id)->first();

            if ($data != null) {

                $finalData = [];

                $finalData[] = $this->common->GetGroupOrdersDetails($data);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $finalData);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function VerifyOTP(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                    'otp' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $group_order_id = $request['group_order_id'];
            $otp = $request['otp'];

            $order = Orders::where('group_order_id', $group_order_id)->where('delivery_otp', $otp)->first();
            if ($order) {
                return $this->common->API_Response(200, __('api_msg.otp_verify'));
            } else {
                return $this->common->API_Response(400, __('api_msg.otp_not_verify'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ResendOTP(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $group_order_id = $request['group_order_id'];

            $order = Orders::where('group_order_id', $group_order_id)
                ->with('customer')
                ->first();


            if (!$order || !$order->customer) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $customer = $order->customer;

            $newOtp = random_int(1000, 9999);
            $order->update([
                'delivery_otp' => $newOtp,
            ]);

            $array = [
                'new-otp' => $newOtp,
            ];

            $NotiStatus = $this->common->BasicNotiConfiguration('resend-otp');
            if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {
                $this->common->SaveNotification('customer', $NotiStatus['type'], $customer['id'], 0, 0, "", $customer['device_token'], $customer['device_type'], $array);
            }

            return $this->common->API_Response(200, __('api_msg.otp_resent'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ChangeOrderStatus(Request $request) //Status - 1-Accepted ,2-Pickup-confirm, 4-Deliverd  ,6- Rejected
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                    'status' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $group_order_id = $request['group_order_id'];
            $status = $request['status'];

            $assignment = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
            $order = Orders::where('group_order_id', $group_order_id)->with('seller', 'customer')->first();

            if (!$assignment || !$order) {
                return $this->common->API_Response(400, __('api_msg.status_not_changes'));
            }

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

            return $this->common->API_Response(200, __('api_msg.status_changes'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetNotifications(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $delivery_boy_id = $request['delivery_boy_id'];

            $user_noti = NotificationTracking::where('type', 2)->where('type_id', $delivery_boy_id)->pluck('notification_id')->toArray();

            $data = Notification::whereNotIn('id', $user_noti)
                ->where(function ($query) use ($delivery_boy_id) {

                    $query->where(function ($q) {
                        $q->where('type', 'admin')
                            ->where('delivery_boy_id', 0);
                    })
                        ->orWhere(function ($q) use ($delivery_boy_id) {
                            $q->where('delivery_boy_id', $delivery_boy_id);
                        });
                });

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ReadNotification(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                    'notification_id' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }


            $noti = new NotificationTracking();
            $noti->type = 2;
            $noti->type_id = $request['delivery_boy_id'];
            $noti->notification_id = $request['notification_id'];
            $noti->status = 1;

            if ($noti->save()) {
                return $this->common->API_Response(200, __('api_msg.notification_read'));
            } else {
                return $this->common->API_Response(400,  __('api_msg.notification_not_read'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function MyProgress(Request $request) // today ,week
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required|numeric',
                    'type' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $delivery_boy_id = $request['delivery_boy_id'];
            $type = $request['type'];

            $query = DeliveryAssignment::where('delivery_boy_id', $delivery_boy_id);

            if ($type == 'today') {
                $query->whereDate('created_at', now()->format('Y-m-d'));
            } else if ($type == 'week') {
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            }

            $totalOrders   = (int) ($query->count() ?? 0);
            $totalEarnings = (int) (clone $query)->where('status', 4)->sum('earning');

            $return = [
                'total_orders' => $totalOrders,
                'total_earning' => $totalEarnings,
            ];
            return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($return));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function CollectCash(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required',
                    'group_order_id' => 'required',
                    'total_price' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $delivery_boy_id = $request['delivery_boy_id'];
            $group_order_id = $request['group_order_id'];
            $total_price = $request['total_price'];

            $boy   = DeliveryBoy::find($delivery_boy_id);
            $orders = Orders::where('group_order_id', $group_order_id)->first();

            if (!$boy || !$orders) {
                return $this->common->API_Response(400, __('api_msg.cash_not_collection'));
            }

            $alreadyCollected = Transaction::where('group_order_id', $group_order_id)->where('type', 2)->exists();
            if ($alreadyCollected) {
                return $this->common->API_Response(400, __('api_msg.already_collected_order'));
            }

            Transaction::create([
                'type' => 2,
                'customer_id' => $orders->customer_id,
                'delivery_boy_id' => $boy->id,
                'group_order_id' => $group_order_id,
                'transaction_id' => 'TXN' . time(),
                'price' => $total_price,
                'status' => 1,
            ]);

            $boy->advance_balance += $total_price;
            $boy->save();

            $orders->payment_status = 2;
            $orders->save();

            $this->common->CreateSettlement($group_order_id);

            return $this->common->API_Response(200, __('api_msg.cash_collection'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function EarningHistory(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $delivery_boy_id = $request['delivery_boy_id'];

            $data = DeliveryAssignment::where('status', 4)->where('delivery_boy_id', $delivery_boy_id)->with('group_order');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $result = [];
                foreach ($data as $value) {
                    $basicInfo = $this->common->BasicCommonDetails($value['group_order_id']);
                    $address = $basicInfo['address'] ?? [];

                    $deliveredAddress = trim(
                        implode(', ', array_filter([
                            $address['address'] ?? null,
                            $address['area'] ?? null,
                            $address['city'] ?? null,
                            $address['state'] ?? null,
                            $address['country'] ?? null,
                        ]))
                    );

                    if (!empty($address['pincode'])) {
                        $deliveredAddress .= ' - ' . $address['pincode'];
                    }
                    $result[] = [
                        'group_order_id'  => $value->group_order_id ?? '',
                        'delivery_status' => $value->status,
                        'order_date_time' => optional($value->group_order)->created_at ? $value->group_order->created_at->format('d M Y h:i A') : '',
                        'distance_km' => "2.1 km",
                        'duration' => "15 min",
                        'payment_method' => $value->group_order->payment_method ?? 0,
                        'earning' => $value['earning'],
                        'delivered_address' => $deliveredAddress,
                        'seller_user_name' => $basicInfo['seller']['user_name'] ?? '',
                        'seller_full_name' => $basicInfo['seller']['full_name'] ?? '',
                        'seller_store_name' => $basicInfo['store']['store_name'] ?? '',

                    ];
                }


                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $result, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function WithdrawalHistory(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'delivery_boy_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $delivery_boy_id = $request['delivery_boy_id'];

            $data = DeliveryWithdrawal::where('type', 2)->where('type_id', $delivery_boy_id);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                foreach ($data as  $value) {
                    $settlementIds = json_decode($value->settlement_ids, true);
                    if (!is_array($settlementIds)) {
                        $settlementIds = [];
                    }

                    $groupOrderIds = Settelment::whereIn('id', $settlementIds)->pluck('group_order_id')->toArray();
                    $orders = Orders::whereIn('group_order_id', $groupOrderIds)->get();
                    $value->total_orders = $orders->count();

                    if ($orders->count() > 0) {

                        $startDate = date('Y-m-d', strtotime($orders->min('created_at')));
                        $endDate   = date('Y-m-d', strtotime($orders->max('created_at')));

                        if ($startDate === $endDate) {
                            $value->period = date('d M Y', strtotime($startDate));
                        } elseif (date('Y', strtotime($startDate)) === date('Y', strtotime($endDate))) {
                            $value->period = date('d M', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
                        } else {
                            $value->period = date('d M Y', strtotime($startDate)) . ' - ' . date('d M Y', strtotime($endDate));
                        }
                    } else {
                        $value->period = '-';
                    }

                    $value->payment_date = date('d M Y', strtotime($value->created_at));
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DownloadReceipt(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'withdrawal_no' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $withdrawal_no = $request['withdrawal_no'];

            $withdrawal = DeliveryWithdrawal::where('withdrawal_no', $withdrawal_no)->first();

            if (!$withdrawal) {
                return $this->common->API_Response(404, __('label.data_not_found'));
            }

            $deliveryBoy = DeliveryBoy::find($withdrawal->type_id);

            $settlementIds = json_decode($withdrawal->settlement_ids);
            $settlements = Settelment::whereIn('id', $settlementIds)->orderBy('created_at', 'asc')->get();

            $orderIds = $settlements->pluck('group_order_id')->toArray();

            $orders = Orders::whereIn('group_order_id', $orderIds)->orderBy('created_at', 'asc')->get();

            $periodFrom = optional($orders->first())->created_at;
            $periodTo   = optional($orders->last())->created_at;
            $result = [
                'type' => 'deliveryboy',
                'withdrawal' => $withdrawal,
                'deliveryBoy' => $deliveryBoy,
                'settlements' => $settlements,
                'totalOrders' => $settlements->count(),
                'totalPaid' => $withdrawal->total_amount,
                'period_from' => $periodFrom,
                'period_to'   => $periodTo,
            ];

            $this->common->DeleteOlderReceipt();

            $pdf = Pdf::loadView('withdrawal_receipt', compact('result'))->setPaper([0, 0, 700, 890]);

            $pdfName = config('app.name') . "-Receipt-" . $withdrawal_no . ".pdf";
            $pdfPath = 'withdrawal_receipt/' . $pdfName;
            Storage::disk('public')->put($pdfPath, $pdf->output());
            $pdfUrl = $this->common->getImage('withdrawal_receipt',  $pdfName);

            return $this->common->API_Response(200, __('api_msg.download_recipet'), ['pdf_url' => $pdfUrl]);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
}
