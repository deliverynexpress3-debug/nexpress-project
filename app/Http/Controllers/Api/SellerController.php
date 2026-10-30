<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Common;
use App\Models\Customer;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\DeliveryWithdrawal;
use App\Models\Hashtag;
use App\Models\Items;
use App\Models\ItemsFaq;
use App\Models\ItemsImages;
use App\Models\Notification;
use App\Models\NotificationTracking;
use App\Models\Orders;
use App\Models\Promocode;
use App\Models\SellerRequest;
use App\Models\Settelment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class SellerController extends Controller
{
    private $folder_seller = "customer";
    private $folder_product = "product";
    private $folder_attributes = "attributes";
    private $folder_promocode = "promocode";
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

            if ($request['type'] == 4) {

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

                );
            }

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $type = $request->type;
            $email = isset($request->email) ? $request->email : '';
            $device_type = isset($request->device_type) ? (int)$request->device_type : 0;
            $device_token = isset($request->device_token) ? $request->device_token : '';

            // Normal
            if ($type == 4) {

                $seller = Customer::where('email', $email)->latest()->first();
                if (isset($seller)) {

                    // Update Device Token && Type
                    Customer::where('id', $seller['id'])->update(['device_token' => $device_token]);
                    Customer::where('id', $seller['id'])->update(['device_type' => $device_type]);

                    $seller['device_type'] = $device_type;
                    $seller['device_token'] = $device_token;

                    if (Hash::check($request['password'], $seller['password'])) {

                        $this->common->imageNameToUrl(array($seller), 'image', $this->folder_seller);

                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($seller));
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
                    'seller_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $seller_id = $request['seller_id'];

            $data = Customer::where('id', $seller_id)->first();
            if (!empty($data) && isset($data) && $data != null) {

                // Image Name to URL
                $this->common->imageNameToUrl(array($data), 'image', $this->folder_seller);

                $otherInfo = SellerRequest::where('customer_id', $seller_id)->first();
                $data['store_name'] = $otherInfo['store_name'] ?? '';
                $data['store_url'] = $otherInfo['store_url'] ?? '';
                $data['store_address'] = $otherInfo['store_address'] ?? '';
                $data['store_logo']    = !empty($otherInfo->store_logo) ? $this->common->getImage($this->folder_seller, $otherInfo->store_logo) : '';

                $ordersInfo = $this->common->SellerOtherProfileInfo($seller_id);

                $data['total_orders'] = $ordersInfo['total_orders'];
                $data['total_earning'] = $ordersInfo['total_earning'];
                $data['avg_order'] = $ordersInfo['avg_orders'];
                $data['today_orders'] = $ordersInfo['today_orders'];
                $data['today_earning'] = $ordersInfo['today_earning'];
                $data['out_of_stock'] = $ordersInfo['out_of_stock'];
                $data['pending_settlement'] = $ordersInfo['pending_settlement'];
                $data['total_settlement']   = $ordersInfo['total_settlement'];
                $data['next_payout_date']    = $ordersInfo['next_payout_date'];

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function UpdateProfile(Request $request)
    {
        try {

            $validation = Validator::make($request->all(), [
                'seller_id' => 'required|numeric',
            ]);

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $seller_id = $request->seller_id;

            $seller = Customer::find($seller_id);

            if (!$seller) {
                return $this->common->API_Response(400, __('label.data_not_found'));
            }

            $array = [];
            $storearray = [];

            /* ================= CUSTOMER UPDATE ================= */

            if (!empty($request->user_name)) {

                $exists = Customer::where('user_name', $request->user_name)
                    ->where('id', '!=', $seller_id)
                    ->exists();

                if ($exists) {
                    return $this->common->API_Response(400, __('api_msg.user_name_exists'));
                }

                $array['user_name'] = $request->user_name;
            }

            if (!empty($request->full_name)) {
                $array['full_name'] = $request->full_name;
            }

            if (!empty($request->email)) {

                $emailExists = Customer::where('email', $request->email)
                    ->where('id', '!=', $seller_id)
                    ->exists();

                if ($emailExists) {
                    return $this->common->API_Response(400, __('api_msg.email_address_already_exists'));
                }

                $array['email'] = $request->email;
            }

            if (!empty($request->password)) {
                $array['password'] = Hash::make($request->password);
            }

            if (!empty($request->country_code) && !empty($request->mobile_number)) {

                $mobileExists = Customer::where('country_code', $request->country_code)
                    ->where('mobile_number', $request->mobile_number)
                    ->where('id', '!=', $seller_id)
                    ->exists();

                if ($mobileExists) {
                    return $this->common->API_Response(400, __('api_msg.mobile_number_already_exists'));
                }

                $array['country_code']  = $request->country_code;
                $array['mobile_number'] = $request->mobile_number;
            }

            if (!empty($request->country_name)) {
                $array['country_name'] = $request->country_name;
            }

            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $old_image = $seller->image;
                $array['image'] = $this->common->saveImage($image, $this->folder_seller, 'seller_');
                $this->common->deleteImageToFolder($this->folder_seller, $old_image);
            }

            if (!empty($request->device_type)) {
                $array['device_type'] = $request->device_type;
            }

            if (!empty($request->device_token)) {
                $array['device_token'] = $request->device_token;
            }

            if (!empty($array)) {
                $seller->update($array);
            }

            /* ================= STORE UPDATE ================= */

            $store = SellerRequest::firstOrNew(['customer_id' => $seller_id]);

            if (!empty($request->store_name)) {
                $storearray['store_name'] = $request->store_name;
            }

            if (!empty($request->store_url)) {
                $storearray['store_url'] = $request->store_url;
            }

            if (!empty($request->store_address)) {
                $storearray['store_address'] = $request->store_address;
            }

            if ($request->hasFile('store_logo')) {
                $store_logo = $request->file('store_logo');
                $old_store_logo = $store->store_logo ?? null;
                $storearray['store_logo'] = $this->common->saveImage($store_logo, $this->folder_seller, 'store_');

                if ($old_store_logo) {
                    $this->common->deleteImageToFolder($this->folder_seller, $old_store_logo);
                }
            }

            if (!empty($storearray)) {
                $store->fill($storearray);
                $store->customer_id = $seller_id;
                $store->save();
            }

            /* ================= FINAL RESPONSE DATA ================= */

            $seller = Customer::find($seller_id);
            $store  = SellerRequest::where('customer_id', $seller_id)->first();

            $this->common->imageNameToUrl([$seller], 'image', $this->folder_seller);

            if ($store) {
                $seller['store_name'] = $store['store_name'] ?? '';
                $seller['store_url'] = $store['store_url'] ?? '';
                $seller['store_address'] = $store['store_address'] ?? '';
                $seller['store_logo']    = !empty($store->store_logo) ? $this->common->getImage($this->folder_seller, $store->store_logo) : '';
            }

            //Send Mail & Send Noti
            $NotiStatus = $this->common->BasicNotiConfiguration('profile-update');
            if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {
                $this->common->SaveNotification('seller', $NotiStatus['type'], 0, $seller['id'], 0, "", $seller['device_token'], $seller['device_type']);
            }

            return $this->common->API_Response(200, __('api_msg.profile_update_successfully'), array($seller));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeleteAccount(Request $request) //0-Off ,1-On
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'seller_id' => 'required|numeric',
                    'status'    => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $sellerId = $request['seller_id'];
            $status = $request['status'];

            $seller = Customer::where('id', $sellerId)->first();

            if (!$seller) {
                return $this->common->API_Response(404, __('label.data_not_found'));
            }

            $seller->status = $status;
            $seller->save();

            if ($status == 1) {
                Items::where('seller_id', $sellerId)->where('total_stock', '>', 0)->update(['status' => 1]);
            } else {
                Items::where('seller_id', $sellerId)->update(['status' => 0]);
            }

            $array = [
                'type' => 'seller-side',
                'status' => $status,
            ];

            $NotiStatus = $this->common->BasicNotiConfiguration('account-delete');
            if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {
                $this->common->SaveNotification('seller', $NotiStatus['type'], 0, $seller['id'], 0, "", $seller['device_token'], $seller['device_type'], $array);
            }
            return $this->common->API_Response(200, __('api_msg.status_changes'), array($seller));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetOrdersList(Request $request) //Status 0-All -1-New Orders ,2-Accepted , 3-Preparing , 4-Ready for Pickup ,5- Handed Over ,6-Deliverd ,7-Cacelled ,8-Rejected
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'seller_id' => 'required|numeric',
                    'status' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $seller_id = $request['seller_id'];
            $status = $request['status'];

            if ($status == 0) {
                $data = Orders::where('seller_id', $seller_id)->with('customer', 'seller', 'customer_address');
            } else {
                $data = Orders::where('seller_id', $seller_id)->where('order_status', $status)->with('customer', 'seller', 'customer_address');
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

            if (count($data) > 0) {

                $finalData = [];

                foreach ($data as $row) {
                    $finalData[] = $this->common->GetSellerOrdersData($row);
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $finalData, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
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

            $data = Orders::where('group_order_id', $group_order_id)->first();

            if ($data != null) {

                $finalData = [];

                $finalData[] = $this->common->GetSellerGroupOrdersDetails($data);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $finalData);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ChangeOrderStatus(Request $request) //Status 2-Accepted ,3-Preparing ,4-Ready-for-pickup ,8-Rejected
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

            $order = Orders::where('group_order_id', $group_order_id)->with('seller', 'customer')->first();

            if (!$order) {
                return $this->common->API_Response(400, __('api_msg.status_not_changes'));
            }

            // Update delivery assignment status
            $order->order_status = $status;
            $order->save();

            $customer = $order->customer;
            $seller = $order->seller;

            $array = [
                'status_type' => 'seller-side',
                'status' => $status,
                'group_order_id' => $order->group_order_id,
                'seller_name' => $seller['user_name'],
                'customer_name' => $customer['user_name'],
            ];
            $Noti = $this->common->BasicNotiConfiguration('order-status-change');

            $array['notification_type'] = $Noti['type'];
            $this->common->OrderTrackingAdd($order->group_order_id, $status);

            if ($Noti['status'] == 1) {
                if ($Noti['send_mail'] == 1) {
                    $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
                }
                if ($Noti['send_notification'] == 1) {
                    $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
                }
            }

            return $this->common->API_Response(200, __('api_msg.status_changes'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    // public function ChangeOrderStatus(Request $request) //Status 2-accepted , 3-preparing , 4-ready-for-pickup ,8-rejected
    // {
    //     try {
    //         $validation = Validator::make(
    //             $request->all(),
    //             [
    //                 'group_order_id' => 'required',
    //                 'status' => 'required',
    //             ],

    //         );
    //         if ($validation->fails()) {
    //             return $this->common->API_Response(400, $validation->errors()->first());
    //         }

    //         $group_order_id = $request['group_order_id'];
    //         $status = $request['status'];

    //         $assignment = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
    //         $order = Orders::where('group_order_id', $group_order_id)->with('seller', 'customer')->first();

    //         if (!$order) {
    //             return $this->common->API_Response(400, __('api_msg.status_not_changes'));
    //         }

    //         // Update delivery assignment status
    //         $order->order_status = $status;
    //         $order->save();

    //         $customer = $order->customer;
    //         $seller = $order->seller;

    //         $array = [
    //             'status_type' => 'seller-side',
    //             'status' => $status,
    //             'group_order_id' => $order->group_order_id,
    //             'seller_name' => $seller['user_name'],
    //             'customer_name' => $customer['user_name'],
    //         ];
    //         $Noti = $this->common->BasicNotiConfiguration('order-status-change');

    //         if ($status == 2) { // Accepted ,Preparing
    //             $this->common->OrderTrackingAdd($order->group_order_id, 2);

    //             if ($Noti['status'] == 1) {
    //                 if ($Noti['send_mail'] == 1) {
    //                     $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
    //                 }
    //                 if ($Noti['send_notification'] == 1) {
    //                     $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
    //                 }
    //             }
    //         } else if ($status == 3) { //Ready for Pickup
    //             $this->common->OrderTrackingAdd($order->group_order_id, 3);

    //             if ($Noti['status'] == 1) {
    //                 if ($Noti['send_mail'] == 1) {
    //                     $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
    //                 }
    //                 if ($Noti['send_notification'] == 1) {
    //                     $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
    //                 }
    //             }
    //         } else if ($status == 5) { //Delivered
    //             $assignment->status = 6;
    //             $assignment->save();
    //             $this->common->OrderTrackingAdd($order->group_order_id, 5);
    //             $this->common->CreateSettlement($group_order_id);

    //             if ($Noti['status'] == 1) {
    //                 if ($Noti['send_mail'] == 1) {
    //                     $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
    //                 }
    //                 if ($Noti['send_notification'] == 1) {
    //                     $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
    //                 }
    //             }
    //         } else if ($status == 6) { //Rejected ,Deny
    //             if ($Noti['status'] == 1) {
    //                 if ($Noti['send_mail'] == 1) {
    //                     $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
    //                 }
    //                 if ($Noti['send_notification'] == 1) {
    //                     $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
    //                 }
    //             }
    //         }
    //         return $this->common->API_Response(200, __('api_msg.status_changes'));
    //     } catch (Exception $e) {
    //         return $this->common->API_Error($e);
    //     }
    // }
    public function GetNotifications(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'seller_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $seller_id = $request['seller_id'];

            $user_noti = NotificationTracking::where('type', 3)->where('type_id', $seller_id)->pluck('notification_id')->toArray();

            $data = Notification::whereNotIn('id', $user_noti)
                ->where(function ($query) use ($seller_id) {

                    $query->where(function ($q) {
                        $q->where('type', 'admin')
                            ->where('seller_id', 0);
                    })
                        ->orWhere(function ($q) use ($seller_id) {
                            $q->where('seller_id', $seller_id);
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
                    'seller_id' => 'required|numeric',
                    'notification_id' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }


            $noti = new NotificationTracking();
            $noti->type = 3;
            $noti->type_id = $request['seller_id'];
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
                    'seller_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $seller_id = $request['seller_id'];

            $data = Orders::where('seller_id', $seller_id)->where('order_status', 6);

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

                foreach ($data as $key => $value) {
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
                        'delivery_status' => $value->order_status,
                        'order_date_time' => optional($value->created_at) ? $value->created_at->format('d M Y h:i A') : '',
                        'payment_method' => $value->payment_method ?? 0,
                        'earning' => $value['seller_commission'],
                        'delivered_address' => $deliveredAddress,

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
                    'seller_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $seller_id = $request['seller_id'];

            $data = DeliveryWithdrawal::where('type', 1)->where('type_id', $seller_id);

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

            $seller = Customer::find($withdrawal->type_id);

            $settlementIds = json_decode($withdrawal->settlement_ids);
            $settlements = Settelment::whereIn('id', $settlementIds)->orderBy('created_at', 'asc')->get();

            $orderIds = $settlements->pluck('group_order_id')->toArray();

            $orders = Orders::whereIn('group_order_id', $orderIds)->orderBy('created_at', 'asc')->get();

            $periodFrom = optional($orders->first())->created_at;
            $periodTo   = optional($orders->last())->created_at;
            $result = [
                'type' => 'seller',
                'withdrawal' => $withdrawal,
                'seller' => $seller,
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
    public function GetDeliveryBoy()
    {
        try {
            $data = DeliveryBoy::where('status', 1)->where('verification_status', 1)->latest()->get();

            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_deliveryboy);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeliveryAssignments(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                    'delivery_boy_id' => 'required',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $group_order_id = $request['group_order_id'];
            $delivery_boy_id = $request['delivery_boy_id'];

            $order = Orders::where('group_order_id', $group_order_id)->with('customer_address', 'customer')->first();
            if (!$order) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $currentTime = date('Y-m-d H:i:s');
            $pickupTime = date('Y-m-d\TH:i', strtotime($currentTime));
            $estimatedDeliveryTime = date('Y-m-d\TH:i', strtotime($currentTime . ' +10 minutes'));
            $pickup_location = $this->common->SellerPickupLocationInfo($group_order_id);

            $assignment = DeliveryAssignment::updateOrCreate(
                [
                    'group_order_id' => $group_order_id
                ],
                [
                    'delivery_boy_id'         => $delivery_boy_id,
                    'pickup_location'         => $pickup_location,
                    'pickup_time'             => $pickupTime,
                    'estimated_delivery_time' => $estimatedDeliveryTime,
                    'earning'                 => $order->delivery_boy_commission,
                    'status'                  => 1,
                ]

            );


            $order->update([
                'is_assigned' => 1
            ]);

            $boy = DeliveryBoy::where('id', $delivery_boy_id)->first();
            $deliveryAdd = $order->customer_address;
            $delivery_address = collect([
                $deliveryAdd['address'] ?? null,
                $deliveryAdd['area'] ?? null,
                $deliveryAdd['city'] ?? null,
                $deliveryAdd['state'] ?? null,
                $deliveryAdd['country'] ?? null,
            ])->filter()->implode(', ');

            $delivery_address .= isset($deliveryAdd['pincode'])
                ? ' - ' . $deliveryAdd['pincode']
                : '';

            $array = [
                'deliveryboy_name' => $boy->full_name,
                'customer_name' => $order->customer->user_name ?? '',
                'delivery_address' => $delivery_address,
                'total_payable_amount' => $order->total_payable_amount,
                'group_order_id' => $order->group_order_id,
                'pickup_time' => $assignment->pickup_time,
                'pickup_location' => $assignment->pickup_location,
                'estimated_delivery_time' => $assignment->estimated_delivery_time,
            ];

            $Noti = $this->common->BasicNotiConfiguration('order-assignment');
            $array['notification_type'] = $Noti['type'];
            $array['status'] = 1;
            if ($Noti['status'] == 1) {
                if ($Noti['send_mail'] == 1) {
                    $this->common->Send_Mail($Noti['type'], $boy['email'], $array);
                }
                if ($Noti['send_notification'] == 1) {
                    $this->common->SaveNotification('deliveryboy', $Noti['type'], 0, 0, $boy['id'], $group_order_id, $boy['device_token'], $boy['device_type'], $array);
                }
            }
            return $this->common->API_Response(200, __('api_msg.delivery_assignments'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    //Product Related
    public function GetBrand()
    {
        try {
            $data = Brand::where('status', 1)->latest()->get();

            if (sizeof($data) > 0) {

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ImageUpload(Request $request) // 1-Upload Items 
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                    'upload' => 'image|mimes:jpeg,png,jpg|max:2048',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request['type'];
            if (isset($request->upload) && $request->upload != 'undefined') {
                $file = $request->file('upload');

                if ($type == 1) {
                    $folder = $this->folder_product;
                }
                $file_name = $this->common->saveImage($file, $folder, 'other_');

                if (isset($file_name)) {
                    $result = $file_name;
                    return $this->common->API_Response(200, __('api_msg.image_upload_success'), $result);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'), []);
                }
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetAttributesByCategory(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'category_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $category_id = $request['category_id'];

            $data = Attributes::whereRaw("FIND_IN_SET(?, category_id)", [$category_id])->where('status', 1)->latest()->get();

            if (count($data) > 0) {

                $this->common->imageNameToUrl($data, 'icon', $this->folder_attributes);

                foreach ($data as $value) {
                    $option = AttributesOption::where('attributes_id', $value['id'])->get();
                    if (count($option) > 0) {

                        $value['attributes_option'] = $option;
                    } else {
                        $value['attributes_option'] = [];
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetCategory()
    {
        try {
            $categories = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->get();


            if ($categories->count() == 0) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $finalData = [];

            foreach ($categories as $category) {

                // Get Sub Categories
                $subCategories = Category::where('parent_category_id', $category->id)
                    ->where('status', 1)
                    ->orderBy('sort_order', 'asc')
                    ->get();

                $categoryData = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'image' => $category->image,
                    'sort_order' => $category->sort_order,
                    'sub_categories' => $subCategories
                ];

                $finalData[] = $categoryData;
            }
            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $finalData);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetItemsList(Request $request) //type - 0-all ,1-In-Stock ,2-Out-Of-Stock
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                    'seller_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request['type'];
            $sellerId = $request['seller_id'];

            $data = Items::where('seller_id', $sellerId);

            if ($type == 1) {
                $data->where('total_stock', '!=', 0)->latest();
            } else if ($type == 2) {
                $data->where('total_stock', '=', 0)->latest();
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

            if (count($data) > 0) {
                $data = $this->common->CommonItemsDetails($data, 0);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function SearchItems(Request $request) //type - 0-all ,1-In-Stock ,2-Out-Of-Stock
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                    'seller_id' => 'required|numeric',
                    'query' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request['type'];
            $seller_id = $request['seller_id'];
            $query = $request['query'];

            $data = Items::where('seller_id', $seller_id);

            if ($type == 1) {
                $data->where('total_stock', '!=', 0);
            } elseif ($type == 2) {
                $data->where('total_stock', 0);
            }

            $data->where(function ($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                    ->orWhere('price', 'like', "%$query%")
                    ->orWhere('unit', 'like', "%$query%")
                    ->orWhere('per_unit', 'like', "%$query%")
                    ->orWhere('sort_description', 'like', "%$query%");
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
                $data = $this->common->CommonItemsDetails($data, 0);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddProduct(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'seller_id' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'brand_id' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'per_unit' => 'required',
                'total_stock' => 'required',
                'video_type' => 'required',
            ];

            $portraitFile = $request->file('portrait_img');
            if ($portraitFile && !$portraitFile->isValid()) {
                $uploadError = $portraitFile->getError();
                $portraitMessage = in_array($uploadError, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)
                    ? 'Please upload a portrait image under 2 MB. JPG and PNG only.'
                    : 'The portrait image could not be uploaded. Please use a JPG or PNG file under 2 MB.';
                return response()->json([
                    'status' => 400,
                    'message' => $portraitMessage,
                    'errors' => $portraitMessage,
                    'result' => [],
                ]);
            }

            if ($request->hasFile('portrait_img')) {
                $rules['portrait_img'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
            } elseif (is_string($request->input('portrait_img')) && trim($request->input('portrait_img')) !== '') {
                $rules['portrait_img'] = 'required|string';
            } else {
                $rules['portrait_img'] = 'required|image|mimes:jpeg,png,jpg|max:2048';
            }

            if ($request->hasFile('landscape_img')) {
                $rules['landscape_img'] = 'image|mimes:jpeg,png,jpg|max:2048';
            }

            if ($request->video_type == 2) {
                $rules['url'] = 'required|url';
            }

            $validator = Validator::make($request->all(), $rules, [
                'portrait_img.required' => 'Please upload a portrait image.',
                'portrait_img.image' => 'Please upload a portrait image as a JPG or PNG file under 2 MB.',
                'portrait_img.mimes' => 'Please upload a portrait image as a JPG or PNG file. Other formats are not allowed.',
                'portrait_img.max' => 'Please upload a portrait image under 2 MB.',
                'portrait_img.uploaded' => 'The portrait image could not be uploaded. Please use a JPG or PNG file under 2 MB.',
            ]);
            if ($validator->fails()) {
                $message = $validator->errors()->first();
                return response()->json([
                    'status' => 400,
                    'message' => $message,
                    'errors' => $message,
                    'result' => [],
                ]);
            }

            $sort_description = $request->sort_description ?? '';
            $long_description = $request->long_description ?? '';

            $url = "";
            if ($request->video_type == 1 && $request->file('url')) {
                $url = $this->common->saveImage($request->file('url'), $this->folder_product, 'vid_');
            } elseif ($request->video_type == 2) {
                $url = $request->url;
            }

            $portrait_img = '';
            if ($request->hasFile('portrait_img')) {
                $files_p = $request->file('portrait_img');
                $portrait_img = $this->common->saveImage($files_p, $this->folder_product, "items_");
                if (!is_string($portrait_img) || $portrait_img === '') {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Portrait image could not be saved.',
                        'errors' => 'Portrait image could not be saved.',
                        'result' => [],
                    ]);
                }
            } else {
                $portrait_img = basename($request->input('portrait_img'));
            }

            $landscape_img = "";
            if ($request->file('landscape_img')) {
                $files_l = $request->file('landscape_img');
                $landscape_img = $this->common->saveImage($files_l, $this->folder_product, "items_");
            }

            $hashtag_id = $this->common->checkHashTag($request->tag_id);
            $tag_id = count($hashtag_id) > 0 ? implode(',', $hashtag_id) : 0;

            $insertData = [
                'name' => $request->name,
                'seller_id' => $request->seller_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'product_type' => 1,
                'price' => $request->price,
                'unit' => $request->unit,
                'per_unit' => $request->per_unit,
                'tag_id' => $tag_id,
                'portrait_img' => $portrait_img,
                'landscape_img' => $landscape_img,
                'tax_include_price' => 0,
                'tax_id' => "",
                'total_allowed_quantity' => 1,
                'minimum_order_quantity' => 1,
                'minimum_free_order_quantity' => 1,
                'is_cod' => $request->is_cod ?? 0,
                'is_cancelable' => $request->is_cancelable ?? 0,
                'is_returnable' => $request->is_returnable ?? 0,
                'enable_stock' => 1,
                'total_stock' => $request->total_stock,
                'stock_status_id' => 0,
                'sort_description' => $sort_description,
                'long_description' => $long_description,
                'video_type' => $request->video_type ?? 1,
                'url' => $url,
                'is_approved' => 1,
                'status' => 1,
            ];
            $itemId = Items::insertGetId($insertData);

            if (!$itemId) {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }

            if (!empty($request->attributes_details)) {
                $attributesData = json_decode($request->attributes_details, true);

                foreach ($attributesData as  $value) {
                    $this->common->SaveItemAttributes($value, $itemId);
                }
            }

            if (!empty($request->faq_details)) {
                $faqData = json_decode($request->faq_details, true);

                if (!empty($faqData)) {

                    foreach ($faqData as $value) {
                        ItemsFaq::insert([
                            'item_id' => $itemId,
                            'question' => $value['question'] ?? '',
                            'answer' => $value['answer'] ?? '',
                            'status' => 1,

                        ]);
                    }
                }
            }

            if (!empty($request->item_images)) {

                $imagesData = json_decode($request->item_images, true);
                if (!empty($imagesData)) {
                    foreach ($imagesData as $value) {
                        ItemsImages::insert([
                            'item_id' => $itemId,
                            'image' => $value ?? '',
                            'status' => 1,
                        ]);
                    }
                }
            }

            $data = Items::find($itemId);

            $this->common->imageNameToUrl([$data], 'portrait_img', $this->folder_product);
            $this->common->imageNameToUrl([$data], 'landscape_img', $this->folder_product);

            if ($data['video_type'] == 1) {
                $data['url'] = $this->common->getImage($this->folder_product, $data['url']);
            }

            return $this->common->API_Response(200, __('api_msg.item_add_succesfully'), array($data));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function EditProduct(Request $request)
    {
        try {
            $rules = [
                'item_id' => 'required',
                'name' => 'required',
                'seller_id' => 'required',
                'category_id' => 'required',
                'sub_category_id' => 'required',
                'brand_id' => 'required',
                'price' => 'required',
                'unit' => 'required',
                'per_unit' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'landscape_img' => 'image|mimes:jpeg,png,jpg|max:2048',
                'total_stock' => 'required',
                'video_type' => 'required',
            ];

            if ($request->video_type == 2) {
                $rules['url'] = 'required|url';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->common->API_Response(400, $validator->errors()->first());
            }
            $itemId = $request['item_id'];

            $item = Items::where('id', $itemId)->first();
            if (!$item) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $updateData = [];

            if ($request->video_type == 1) {
                if ($request->file('url')) {

                    if (!empty($item->url)) {
                        $this->common->deleteImageToFolder($this->folder_product,  basename($item->url));
                    }
                    $updateData['url'] = $this->common->saveImage($request->url, $this->folder_product, 'vid_');
                } else {
                    $updateData['url'] = $item->url;
                }
            } else {
                $updateData['url'] = $request->url;
            }

            if ($request->file('portrait_img')) {
                $oldImage = $item->portrait_img;
                $updateData['portrait_img'] = $this->common->saveImage($request->portrait_img, $this->folder_product, 'items_');
                $this->common->deleteImageToFolder($this->folder_product, basename($oldImage));
            }

            if ($request->file('landscape_img')) {
                $oldImage = $item->landscape_img;
                $updateData['landscape_img'] = $this->common->saveImage($request->landscape_img, $this->folder_product, 'items_');
                $this->common->deleteImageToFolder($this->folder_product, basename($oldImage));
            }

            $old_hashtag = explode(',', $item['tag_id']);
            $old_hashtag = array_filter($old_hashtag);

            if (!empty($old_hashtag)) {
                Hashtag::whereIn('id', $old_hashtag)->decrement('total_used', 1);
            }

            $hashtag_id = $this->common->checkHashTag($request['tag_id']);
            $updateData['tag_id'] = count($hashtag_id) > 0 ? implode(',', $hashtag_id) : '';

            $sort_description = $request->sort_description ?? '';
            $long_description = $request->long_description ?? '';

            $updateData = array_merge($updateData, [
                'name' => $request->name,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'brand_id' => $request->brand_id,
                'price' => $request->price,
                'unit' => $request->unit,
                'per_unit' => $request->per_unit,
                'is_cod' => $request->is_cod ?? 0,
                'is_cancelable' => $request->is_cancelable ?? 0,
                'is_returnable' => $request->is_returnable ?? 0,
                'total_stock' => $request->total_stock,
                'sort_description' => $sort_description,
                'long_description' => $long_description,
                'video_type' => $request->video_type ?? 1,
            ]);

            Items::where('id', $itemId)->update($updateData);

            if (!empty($request->attributes)) {

                $attributeArray = json_decode($request->attributes_details, true);

                if (is_array($attributeArray)) {
                    $this->common->UpdateItemAttributes($attributeArray, $itemId);
                }
            }

            $itemData = Items::find($itemId);

            $this->common->imageNameToUrl([$itemData], 'portrait_img', $this->folder_product);
            $this->common->imageNameToUrl([$itemData], 'landscape_img', $this->folder_product);

            if ($itemData['video_type'] == 1) {
                $data['url'] = $this->common->getImage($this->folder_product, $itemData['url']);
            }

            return $this->common->API_Response(200, __('api_msg.item_edit_succesfully'), array($itemData));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeleteProduct(Request $request) //Status -0-Un-available,1-Available
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                    'status' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $itemId = $request['item_id'];

            $item = Items::where('id', $itemId)->first();

            if (!$item) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            if ($request->status == 1 && $item->total_stock == 0) {
                return $this->common->API_Response(400, __('api_msg.manage_stock_before_active'));
            }

            $item->status = $request->status;
            $item->save();

            $statusMessage = $request->status == 1 ? __('api_msg.item_activated') : __('api_msg.item_deactivated');

            return $this->common->API_Response(200, $statusMessage);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetPromocode(Request $request) //Status -0-InActive, 1-Active, 2-Expried 3-All	
    {
        try {
            $this->common->ActiveExpriedPromocodes();

            $validation = Validator::make(
                $request->all(),
                [
                    'status' => 'required',
                    'seller_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $seller_id = $request['seller_id'];
            $status = $request['status'];

            if ($status == 3) {
                $data = Promocode::where('seller_id', $seller_id);
            } else {
                $data = Promocode::where('seller_id', $seller_id)->where('status', $status);
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

            if (count($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_promocode);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddPromocode(Request $request) //Type 1-Amount ,2-Percentage
    {
        try {
            $rules = [
                'seller_id'       => 'required',
                'name'            => 'required',
                'type'            => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'valid_from'      => 'required|date',
                'valid_until'     => 'required|date|after_or_equal:valid_from',
                'usage_limit'     => 'required|numeric',
            ];

            // If usage_limit = 1 then require user_limit_per_user
            if ($request->usage_limit == 1) {
                $rules['user_limit_per_user'] = 'required|numeric|min:1';
            }

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $image = '';
            if (isset($request['image']) && $request['image'] != null) {
                $file = $request->file('image');
                $image = $this->common->saveImage($file, $this->folder_promocode, 'promocode_');
            }

            $user_limit_per_user = ($request->usage_limit == 1) ? $request->user_limit_per_user : 0;

            $insertData = [
                'seller_id' => $request->seller_id,
                'name' => $request->name,
                'promo_code' => Str::random(6),
                'type' => $request->type,
                'discount_amount' => $request->discount_amount,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'usage_limit' => $request->usage_limit,
                'user_limit_per_user' => $user_limit_per_user,
                'image' => $image,
                'status' => 1,
            ];

            $insertId = Promocode::insertGetId($insertData);

            if ($insertId) {
                $data = Promocode::find($insertId);

                $this->common->imageNameToUrl(array($data), 'image', $this->folder_promocode);

                return $this->common->API_Response(200, __('api_msg.promocode_added'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function EditPromocode(Request $request)
    {
        try {
            $rules = [
                'promocode_id'    => 'required',
                'seller_id'       => 'required',
                'name'            => 'required',
                'type'            => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'valid_from'      => 'required|date',
                'valid_until'     => 'required|date|after_or_equal:valid_from',
                'usage_limit'     => 'required|numeric',
            ];

            // If usage_limit = 1 then require user_limit_per_user
            if ($request->usage_limit == 1) {
                $rules['user_limit_per_user'] = 'required|numeric|min:1';
            }

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $promocodeId = $request['promocode_id'];

            $data = Promocode::where('id', $promocodeId)->first();
            if (!$data) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $updateData = [];

            if ($request->file('image')) {
                $oldImage = $data->image;
                $updateData['image'] = $this->common->saveImage($request['image'], $this->folder_promocode, 'promocode_');
                $this->common->deleteImageToFolder($this->folder_product, basename($oldImage));
            }
            $user_limit_per_user = ($request->usage_limit == 1) ? $request->user_limit_per_user : 0;

            $updateData = array_merge($updateData, [
                'name' => $request->name,
                'type' => $request->type,
                'discount_amount' => $request->discount_amount,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'usage_limit' => $request->usage_limit,
                'user_limit_per_user' => $user_limit_per_user,
            ]);

            Promocode::where('id', $promocodeId)->update($updateData);

            $data = Promocode::find($promocodeId);

            $this->common->imageNameToUrl(array($data), 'image', $this->folder_promocode);

            return $this->common->API_Response(200, __('api_msg.promocode_edit_succesfully'), array($data));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeletePromocode(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'promocode_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $promocodeId = $request['promocode_id'];

            $promocode = Promocode::find($promocodeId);

            if (!$promocode) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            Orders::where('promo_codes_id', $promocodeId)->update(['promo_codes_id' => 0]);

            $this->common->deleteImageToFolder($this->folder_promocode, $promocode['image']);
            $promocode->delete();

            return $this->common->API_Response(200, __('api_msg.promocode_delete_succesfully'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
}
