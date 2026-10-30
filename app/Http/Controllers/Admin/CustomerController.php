<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class CustomerController extends Controller // Login Type : 1- OTP, 2- Google, 3- Apple, 4- Normal
{
    private $folder = "customer";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];
                $input_login_type = $request['input_login_type'];

                $query = Customer::where('is_seller', 0)->latest();

                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('user_name', 'LIKE', "%{$input_search}%")->orWhere('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%")->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_login_type != "all") {
                    $query->where('type', $input_login_type);
                }
                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $user_delete = __('label.delete_user');

                        $delete = '<form onsubmit="return confirm(\'' . $user_delete . '\');" method="POST" action="' . route('admin.customer.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('admin.customer.address.index', [$row->id]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-location-dot fa-xl p-1"></i>';
                        $btn .= '</a>';
                        $btn .= '<a href="' . route('admin.customer.edit', [$row->id]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('email', function ($row) {
                        $Email = $this->common->MaskEmail();
                        return Demo_Mode() != 1 ? $Email($row->email) : $row->email;
                    })
                    ->addColumn('mobile_number', function ($row) {
                        $MobileNumber = $this->common->MaskMobileNumber();
                        return Demo_Mode() != 1 ? $MobileNumber($row->mobile_number) : $row->mobile_number;
                    })
                    ->addColumn('register_date', function ($row) {
                        $date =  $this->common->GetDateTime($row->created_at);
                        return $date;
                    })
                    ->make(true);
            }
            return view('admin.customer.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {

            return view('admin.customer.add');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_customer|email',
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    Rule::unique('tbl_customer')->where(function ($query) use ($request) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number);
                    }),
                ],
                'password' => 'required|min:4',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'customer_');
            }

            $email_array = explode('@', $request['email']);
            $requestData['user_name'] = $this->common->user_name($email_array[0]);
            $requestData['password'] = Hash::make($request['password']);
            $requestData['is_seller'] = 0;
            $requestData['type'] = 4;
            $requestData['device_type'] = 0;
            $requestData['device_token'] = "";
            $requestData['gst_number'] = "";
            $requestData['gst_firm_name'] = "";
            $requestData['printer_mac_address'] = "";
            $requestData['status'] = 1;

            $user_data = Customer::updateOrCreate(['id' => $requestData['id']], $requestData);

            if (isset($user_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_add_customer')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_customer')));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {

            $params['result'] = Customer::where('id', $id)->first();

            if (isset($params['result']['id'])) {

                $this->common->imageNameToUrl(array($params['result']), 'image', $this->folder);
                return view('admin.customer.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $existingUser = Customer::find($id);
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_customer,email,' . $id,
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    Rule::unique('tbl_customer')->where(function ($query) use ($request, $id) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number)
                            ->where('id', '!=', $id);
                    }),
                ],
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'customer_');

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            $requestData = Arr::except($requestData, ['old_image']);

            if (!empty($request->password)) {
                $requestData['password'] = Hash::make($request->password);
            } else {
                $requestData['password'] = $existingUser->password;
            }

            $User_data = Customer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($User_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_customer')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_customer')));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Customer::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();

                Customer::CustomerDeleteAllData($id);
            }

            return redirect()->route('admin.customer.index')->with('success', __('label.customer_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Address
    public function AddressIndex($user_id, Request $request)
    {
        try {

            $params['data'] = [];
            $params['customer_id'] = $user_id;

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                if (isset($input_search)) {
                    $data = CustomerAddress::query()
                        ->where('customer_id', $user_id)
                        ->when($input_search, function ($query) use ($input_search) {
                            $query->where('address', 'LIKE', "%{$input_search}%")
                                ->orWhere('area', 'LIKE', "%{$input_search}%")
                                ->orWhere('city', 'LIKE', "%{$input_search}%")
                                ->orWhere('state', 'LIKE', "%{$input_search}%")
                                ->orWhere('country', 'LIKE', "%{$input_search}%")
                                ->orWhere('pincode', 'LIKE', "%{$input_search}%");
                        })
                        ->latest()->get();
                } else {
                    $data = CustomerAddress::where('customer_id', $user_id)->latest()->get();
                }


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) use ($user_id) {

                        $address_delete = __('label.delete_address');

                        $delete = '<form onsubmit="return confirm(\'' . $address_delete . '\');" method="POST" action="' . route('admin.customer.address.delete', [$user_id, $row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_address" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-user_id="' . $row->customer_id . '" data-type="' . $row->type . '" data-address="' . $row->address . '" data-area="' . $row->area . '" data-city="' . $row->city . '" data-state="' . $row->state . '" data-country="' . $row->country . '" data-pincode="' . $row->pincode . '" data-latitude="' . $row->latitude . '" data-longitude="' . $row->longitude . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $activeLabel = __('label.show');
                        $inactiveLabel = __('label.hide');

                        $statusBtn = '';

                        if ($row->status == 1) {
                            $statusBtn = "<button type='button' id='{$row->id}' onclick='change_status({$row->id})' class='show-btn'>{$activeLabel}</button>";
                        } else {
                            $statusBtn = "<button type='button' id='{$row->id}' onclick='change_status({$row->id})' class='hide-btn'>{$inactiveLabel}</button>";
                        }

                        return $statusBtn;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.customer.address', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function AddressStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'customer_id' => 'required|numeric',
                'type' => 'required',
                'address' => 'required',
                'area' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pincode' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['status'] = 1;

            $data = CustomerAddress::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.save_add_address')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_address')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function AddressUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'address' => 'required',
                'area' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required',
                'pincode' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $address = CustomerAddress::where('id', $request['id'])->first();
            if (isset($address['id'])) {

                $address['customer_id'] = $request['customer_id'];
                $address['type'] = $request['type'];
                $address['address'] = $request['address'];
                $address['area'] = $request['area'];
                $address['city'] = $request['city'];
                $address['state'] = $request['state'];
                $address['country'] = $request['country'];
                $address['pincode'] = $request['pincode'];
                $address['latitude'] = $request['latitude'];
                $address['longitude'] = $request['longitude'];

                if ($address->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_address')]);
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_address')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_address')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function AddressDelete($user_id, $id)
    {
        try {

            CustomerAddress::where('id', $id)->delete();
            return redirect()->route('admin.customer.address.index', [$user_id])->with('success', __('label.address_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function AddressStatus($id)
    {
        try {

            $data = CustomerAddress::where('id', $id)->first();
            if (isset($data)) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
