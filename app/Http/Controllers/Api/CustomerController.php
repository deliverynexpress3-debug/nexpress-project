<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Exception;

// Login Type : 1- OTP, 2- Goggle, 3- Apple, 4- Normal
class CustomerController extends Controller
{
    private $folder_customer = "customer";
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

    public function Registration(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [

                    'full_name' => 'required|min:2',
                    'email' => 'required|email|unique:tbl_customer',
                    'password' => 'required|min:4',
                    'country_code' => 'required',
                    'country_name' => 'required',
                    'mobile_number' => [
                        'required',
                        Rule::unique('tbl_customer')->where(function ($query) use ($request) {
                            return $query->where('country_code', $request->country_code)
                                ->where('mobile_number', $request->mobile_number);
                        }),
                    ],
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_name = explode('@', $request['email']);
            $insert = array(
                'is_seller' => 0,
                'user_name' => $this->common->user_name($user_name[0]),
                'full_name' => $request['full_name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'country_code' => $request['country_code'],
                'mobile_number' => $request['mobile_number'],
                'country_name' => $request['country_name'],
                'image' => "",
                'device_type' => isset($request->device_type) ? $request->device_type : 0,
                'device_token' => isset($request->device_token) ? $request->device_token : '',
                'wallet_amount' => 0,
                'gst_number' => "",
                'gst_firm_name' => "",
                'printer_mac_address' => "",
                'type' => 4,
                'status' => 1,
            );
            $customer_id = Customer::insertGetId($insert);

            if (isset($customer_id)) {

                $customer = Customer::where('id', $customer_id)->first();
                if (isset($customer)) {

                    $NotiStatus = $this->common->BasicNotiConfiguration('register');
                    if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_mail'] == 1) {
                        $this->common->Send_Mail($NotiStatus['type'], $customer['email']);
                    }

                    $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);

                    return $this->common->API_Response(200, __('api_msg.user_registration_sucessfuly'), array($customer));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
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
            } elseif ($request['type'] == 2 || $request['type'] == 3) {

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

                );
            }
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $image = '';
            if (isset($request['image']) && $request['image'] != null) {
                $file = $request->file('image');
                $image = $this->common->saveImage($file, $this->folder_customer, 'customer_');
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
            $gst_number = isset($request->gst_number) ? $request->gst_number : '';
            $gst_firm_name = isset($request->gst_firm_name) ? $request->gst_firm_name : '';


            // OTP
            if ($type == 1) {

                $customer = Customer::where('mobile_number', $mobile_number)->where('type', $type)->latest()->first();
                if (isset($customer) && $customer != null) {

                    // Update Device Token && Type
                    Customer::where('id', $customer['id'])->update(['device_token' => $device_token]);
                    Customer::where('id', $customer['id'])->update(['device_type' => $device_type]);

                    $customer['device_type'] = $device_type;
                    $customer['device_token'] = $device_token;

                    $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);
                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($customer));
                } else {

                    $insert = array(
                        'is_seller' => 0,
                        'user_name' => $this->common->user_name($mobile_number),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => $password,
                        'country_code' => $country_code,
                        'mobile_number' => $mobile_number,
                        'country_name' => $country_name,
                        'image' => $image,
                        'device_type' => $device_type,
                        'device_token' => $device_token,
                        'gst_number' => $gst_number,
                        'gst_firm_name' => $gst_firm_name,
                        'printer_mac_address' => "",
                        'type' => $type,
                    );
                    $customer_id = Customer::insertGetId($insert);

                    if (isset($customer_id)) {

                        $customer = Customer::where('id', $customer_id)->first();
                        if (isset($customer)) {

                            $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);
                            return $this->common->API_Response(200, __('api_msg.login_successfully'), array($customer));
                        } else {
                            return $this->common->API_Response(400, __('label.data_not_save'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('label.data_not_save'));
                    }
                }
            }

            if ($type == 2 || $type == 3) {

                $customer = Customer::where('email', $email)->latest()->first();

                if (isset($customer) && $customer != null) {

                    // Update Device Token && Type
                    Customer::where('id', $customer['id'])->update(['device_token' => $device_token]);
                    Customer::where('id', $customer['id'])->update(['device_type' => $device_type]);

                    $customer['device_type'] = $device_type;
                    $customer['device_token'] = $device_token;

                    $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);

                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($customer));
                } else {

                    $email_array = explode('@', $request->email);
                    $insert = array(
                        'is_seller' => 0,
                        'user_name' => $this->common->user_name($email_array[0]),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => "",
                        'country_code' => "",
                        'mobile_number' => "",
                        'country_name' => "",
                        'image' =>  $this->common->generateLetterAvatar($email_array[0], $this->folder_customer, 'customer_'),
                        'device_type' => $device_type,
                        'device_token' => $device_token,
                        'type' => $type,
                        'gst_number' => $gst_number,
                        'gst_firm_name' => $gst_firm_name,
                        'printer_mac_address' => "",
                        'status' => 1,
                    );
                    $customer_id = Customer::insertGetId($insert);

                    if (isset($customer_id)) {

                        $customer = Customer::where('id', $customer_id)->first();

                        $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);

                        // // Send Mail
                        if ($type == 2) {
                            $NotiStatus = $this->common->BasicNotiConfiguration('login');
                            if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_mail'] == 1) {
                                $this->common->Send_Mail($NotiStatus['type'], $customer['email']);
                            }
                        }

                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($customer));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_save'));
                    }
                }
            }

            // Normal
            if ($type == 4) {

                $customer = Customer::where('email', $email)->latest()->first();
                if (isset($customer)) {

                    // Update Device Token && Type
                    Customer::where('id', $customer['id'])->update(['device_token' => $device_token]);
                    Customer::where('id', $customer['id'])->update(['device_type' => $device_type]);

                    $customer['device_type'] = $device_type;
                    $customer['device_token'] = $device_token;

                    if (Hash::check($request->password, $customer->password)) {

                        $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);

                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($customer));
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
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request['customer_id'];

            $customer_data = Customer::where('id', $customer_id)->first();
            if (!empty($customer_data) && isset($customer_data) && $customer_data != null) {

                // Image Name to URL
                $this->common->imageNameToUrl(array($customer_data), 'image', $this->folder_customer);

                $customer_data['customer_address'] = CustomerAddress::where('customer_id', $customer_data['id'])->orderBy('id', 'desc')->get()->toArray();

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($customer_data));
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

            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];
            $data = Customer::where('id', $customer_id)->first();

            $array = array();
            if (!empty($data) && isset($data) && $data != null) {


                if (isset($request->user_name) && $request->user_name != '') {

                    $check = Customer::where('user_name', $request->user_name)->first();
                    if (isset($check) && $check != null) {
                        if ($check->id == $data->id) {
                            $array['user_name'] = $request->user_name;
                        } else {
                            return $this->common->API_Response(400, __('api_msg.user_name_exists'));
                        }
                    } else {
                        $array['user_name'] = $request->user_name;
                    }
                }
                if (isset($request->full_name) && $request->full_name != '') {
                    $array['full_name'] = $request->full_name;
                }
                if (isset($request['email']) && $request['email'] != '') {

                    $check_email = Customer::where('id', '!=', $customer_id)->where('email', $request['email'])->first();
                    if (isset($check_email) && $check_email != null) {
                        return $this->common->API_Response(400, __('api_msg.email_address_already_exists'));
                    }

                    $array['email'] = $request['email'];
                }
                if (isset($request->password) && $request->password != '') {
                    $array['password'] = hash::make($request->password);
                }
                if (isset($request['country_code']) && $request['country_code'] != '' && isset($request['mobile_number']) && $request['mobile_number'] != '') {

                    $check_mobile = Customer::where('id', '!=', $customer_id)->where('country_code', $request['country_code'])->where('mobile_number', $request['mobile_number'])->first();
                    if (isset($check_mobile) && $check_mobile != null) {
                        return $this->common->API_Response(400, __('api_msg.mobile_number_already_exists'));
                    }

                    $array['country_code'] = $request['country_code'];
                    $array['mobile_number'] = $request['mobile_number'];
                }
                if (isset($request->country_name) && $request->country_name != '') {
                    $array['country_name'] = $request->country_name;
                }
                if (isset($request->image) && $request->file('image') != '') {

                    $image = $request->file('image');
                    $old_image = $data['image'];
                    $array['image'] = $this->common->saveImage($image, $this->folder_customer, 'customer_');
                    $this->common->deleteImageToFolder($this->folder_customer, $old_image);
                }
                if (isset($request->device_type) && $request->device_type != '') {
                    $array['device_type'] = $request->device_type;
                }
                if (isset($request->device_token) && $request->device_token != '') {
                    $array['device_token'] = $request->device_token;
                }
                if (isset($request->gst_number) && $request->gst_number != '') {
                    $array['gst_number'] = $request->gst_number;
                }
                if (isset($request->gst_firm_name) && $request->gst_firm_name != '') {
                    $array['gst_firm_name'] = $request->gst_firm_name;
                }
                Customer::where('id', $customer_id)->update($array);

                $customer = Customer::where('id', $customer_id)->first();
                $this->common->imageNameToUrl(array($customer), 'image', $this->folder_customer);

                //Send Mail & Send Noti
                $NotiStatus = $this->common->BasicNotiConfiguration('profile-update');
                if ($NotiStatus && $NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {
                    $this->common->SaveNotification('customer', $NotiStatus['type'], $customer['id'], 0, 0, "", $customer['device_token'], $customer['device_type']);
                }

                return $this->common->API_Response(200, __('api_msg.profile_update_successfully'), array($customer));
            } else {
                return $this->common->API_Response(400, __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddNewAddress(Request $request)
    {

        $validation = Validator::make(
            $request->all(),
            [
                'mode' => 'required',
                'customer_id' => 'required|numeric',
                'type' => 'required|numeric',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'area' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pincode' => 'required',
            ],

        );
        if ($validation->fails()) {
            return $this->common->API_Response(400, $validation->errors()->first());
        }

        $lat = round($request->latitude, 6);
        $lng = round($request->longitude, 6);

        if ($request->mode == 'on') {
            $duplicate = CustomerAddress::selectRaw(
                "id, (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude))
                    * cos(radians(longitude) - radians(?))
                    + sin(radians(?)) * sin(radians(latitude))
                )) AS distance",
                [$lat, $lng, $lat]
            )
                ->where('customer_id', $request->customer_id)
                ->having('distance', '<', 0.05) // 50 meters
                ->first();

            if ($duplicate) {

                CustomerAddress::where('id', $duplicate->id)->update([
                    'type'      => $request->type,
                    'address'   => $request->address,
                    'area'      => $request->area,
                    'city'      => $request->city,
                    'state'     => $request->state,
                    'country'   => $request->country,
                    'pincode'   => $request->pincode,
                    'latitude'  => $lat,
                    'longitude' => $lng,
                ]);
                return $this->common->API_Response(200, __('api_msg.address_updated'));
            }
        }

        $insert = new CustomerAddress();
        $insert->customer_id = $request->customer_id;
        $insert->type = $request->type;
        $insert->address = $request->address;
        $insert->area = $request->area;
        $insert->city = $request->city;
        $insert->state = $request->state;
        $insert->country = $request->country;
        $insert->pincode = $request->pincode;
        $insert->latitude = $request->latitude;
        $insert->longitude = $request->longitude;
        $insert->status = 1;

        if ($insert->save()) {
            return $this->common->API_Response(200, __('api_msg.new_address_added'));
        }

        return $this->common->API_Response(400, __('api_msg.data_not_save'));
    }
    public function GetCustomerAddress(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request['customer_id'];

            $data = CustomerAddress::where('customer_id', $customer_id)->where('status', 1)->get();
            if (sizeof($data) > 0) {

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function EditAddress(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required',
                    'address_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request->customer_id;
            $address_id = $request->address_id;
            $array = array();

            $data = CustomerAddress::where('id', $address_id)->where('customer_id', $customer_id)->first();
            if (!empty($data)) {

                if (isset($request->type) && $request->type != 0) {
                    $array['type'] = $request->type;
                }
                if (isset($request->address) && $request->address != '') {
                    $array['address'] = $request->address;
                }
                if (isset($request->latitude) && $request->latitude != '') {
                    $array['latitude'] = $request->latitude;
                }
                if (isset($request->longitude) && $request->longitude != '') {
                    $array['longitude'] = $request->longitude;
                }
                if (isset($request->area) && $request->area != '') {
                    $array['area'] = $request->area;
                }
                if (isset($request->city) && $request->city != '') {
                    $array['city'] = $request->city;
                }
                if (isset($request->state) && $request->state != '') {
                    $array['state'] = $request->state;
                }
                if (isset($request->country) && $request->country != '') {
                    $array['country'] = $request->country;
                }
                if (isset($request->pincode) && $request->pincode != '') {
                    $array['pincode'] = $request->pincode;
                }
                CustomerAddress::where('id', $address_id)->update($array);
                return $this->common->API_Response(200, __('api_msg.address_updated'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DeleteAddress(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required',
                    'address_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request->customer_id;
            $address_id = $request->address_id;

            $data = CustomerAddress::where('id', $address_id)->where('customer_id', $customer_id)->first();
            if (isset($data) && $data != null) {
                $data->delete();
                return $this->common->API_Response(200, __('api_msg.address_deleted'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_deleted'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ForgotPassword(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [

                    'email' => 'required|email',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }


            $email = $request['email'];

            $data = Customer::where('email', $email)->latest()->first();
            if (isset($data) && $data != null) {

                $new_password = rand(100000, 999999);
                $data['password'] = Hash::make($new_password);
                if ($data->save()) {
                    $array = [
                        'new_password' => $new_password,
                        'user_name' => $data['user_name'],
                    ];

                    $NotiStatus = $this->common->BasicNotiConfiguration('forgot-password');
                    if ($NotiStatus && $NotiStatus['status'] == 1) {
                        if ($NotiStatus['send_mail'] == 1) {
                            $this->common->Send_Mail($NotiStatus['type'], $data['email'], $array);
                        }
                        if ($NotiStatus['send_notification'] == 1) {
                            $this->common->SaveNotification('customer', $NotiStatus['type'], $data['id'], 0, 0, "", $data['device_token'], $data['device_type']);
                        }
                    }

                    return $this->common->API_Response(200, __('api_msg.forgot_password_msg'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
}
