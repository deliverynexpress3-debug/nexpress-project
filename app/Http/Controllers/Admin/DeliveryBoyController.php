<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Compensation;
use App\Models\Customer;
use App\Models\DeliveryBoy;
use App\Models\Zones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class DeliveryBoyController extends Controller
{
    private $folder = "delivery_boy";
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
                $input_status = $request['input_status_type'];
                $input_industires_id = $request['input_industires_id'];

                $query = DeliveryBoy::where('verification_status', 1);


                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('user_name', 'LIKE', "%{$input_search}%")->orWhere('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%")->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_industires_id != "all" && $input_industires_id != null) {
                    $query->where('industries_id', $input_industires_id);
                }
                if ($input_status != "all") {
                    $query->where('status', $input_status);
                }

                $data = $query->latest()->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delivery_boy_delete = __('label.delete_delivery_boy');

                        $delete = '<form onsubmit="return confirm(\'' . $delivery_boy_delete . '\');" method="POST" action="' . route('admin.deliveryboy.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';

                        $btn .= '<a href="' . route('admin.deliveryboy.edit', [$row->id]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';

                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('req_status', function ($row) {

                        $optionsHtml = "<select onchange='change_status(\"{$row->id}\", this.value)' class='form-control'>";
                        $optionsHtml .= "<option value='0'" . ($row->status == 0 ? ' selected' : '') . ">" . __('label.unavailable') . "</option>";
                        $optionsHtml .= "<option value='1'" . ($row->status == 1 ? ' selected' : '') . ">" . __('label.available') . "</option>";
                        $optionsHtml .= "<option value='2'" . ($row->status == 2 ? ' selected' : '') . ">" . __('label.busy') . "</option>";
                        $optionsHtml .= "<option value='3'" . ($row->status == 3 ? ' selected' : '') . ">" . __('label.on_delivery') . "</option>";
                        $optionsHtml .= "</select>";
                        return $optionsHtml;
                    })
                    ->addColumn('status', function ($row) {
                        $unavailable = __('label.unavailable');
                        $available = __('label.available');
                        $busy = __('label.busy');
                        $on_delivery = __('label.on_delivery');

                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->status == 0) {
                            $btn .= '<span class="status-badge badge-danger mr-1">' . $unavailable . '</span>';
                        } else if ($row->status == 1) {
                            $btn .= '<span class="status-badge badge-success mr-1">' . $available . '</span>';
                        } else if ($row->status == 2) {
                            $btn .= '<span class="status-badge badge-warning mr-1">' . $busy . '</span>';
                        } else if ($row->status == 3) {
                            $btn .= '<span class="status-badge badge-info mr-1">' . $on_delivery . '</span>';
                        }
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
                    ->editColumn('zone_id', function ($row) {
                        if ($row->zone_id) {
                            $zoneId = explode(',', $row->zone_id);
                            $zone = Zones::whereIn('id', $zoneId)->pluck('zone_name')->toArray();
                            return implode(', ', $zone);
                        }

                        return '-'; // Return dash if no zone_id exists
                    })
                    ->rawColumns(['action', 'status', 'req_status', 'zone_id'])
                    ->make(true);
            }
            return view('admin.deliveryboy.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['zone'] = Zones::where('status', 1)->latest()->get();

            return view('admin.deliveryboy.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_delivery_boy|email',
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    Rule::unique('tbl_delivery_boy')->where(function ($query) use ($request) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number);
                    }),
                ],
                'password' => 'required|min:4',
                'vehicle_name' => 'required',
                'vehicle_number' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',

            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }


            $requestData = $request->all();

            if (isset($request['image']) && $request['image'] != null) {
                $files = $request['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'delivery_boy_');
            } else {
                $requestData['image'] = "";
            }

            // if ($request['zone_id'] != null) {
            //     $zoneId = implode(',', $requestData['zone_id']);
            //     $requestData['zone_id'] = $zoneId;
            // } else {
            //     $requestData['zone_id'] = "";
            // }
            $requestData['zone_id'] = "";

            $email_array = explode('@', $requestData['email']);
            $requestData['user_name'] = $this->common->delivery_boy_user_name($email_array[0]);
            $requestData['password'] = Hash::make($requestData['password']);
            $requestData['device_type'] = 0;
            $requestData['device_token'] = "";
            $requestData['type'] = 4;
            $requestData['verification_status'] = 1;
            $requestData['status'] = 1;

            $user_data = DeliveryBoy::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($user_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_add_delivery_boy')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_delivery_boy')));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {
            $params['zone'] = Zones::where('status', 1)->latest()->get();
            $params['result'] = DeliveryBoy::where('id', $id)->first();

            if (isset($params['result']['id'])) {

                $this->common->imageNameToUrl(array($params['result']), 'image', $this->folder);

                return view('admin.deliveryboy.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id, Request $request)
    {
        try {
            $data = DeliveryBoy::where('id', $id)->first();
            if (isset($data)) {

                $data->update(['status' => $request['status']]);

                return response()->json(['status' => 200, 'success' => __('label.status_changed')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $existingUser = DeliveryBoy::find($id);
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_delivery_boy,email,' . $id,
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    Rule::unique('tbl_delivery_boy')->where(function ($query) use ($request, $id) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number)
                            ->where('id', '!=', $id);
                    }),
                ],
                'vehicle_name' => 'required',
                'vehicle_number' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',

            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            // if ($requestData['zone_id'] != null) {
            //     $zoneId = implode(',', $requestData['zone_id']);
            //     $requestData['zone_id'] = $zoneId;
            // }
            $requestData['zone_id'] = "";

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'delivery_boy_');

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            $requestData = Arr::except($requestData, ['old_image']);

            if (!empty($request->password)) {
                $requestData['password'] = Hash::make($request->password);
            } else {
                $requestData['password'] = $existingUser->password; // make sure you have $existingUser
            }

            $deliveryboy_data = DeliveryBoy::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($deliveryboy_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_delivery_boy')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_delivery_boy')));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $data = DeliveryBoy::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();

                DeliveryBoy::DeleteAllData($id);
            }

            return redirect()->route('admin.deliveryboy.index')->with('success', __('label.delivery_boy_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function change_status($id, Request $request)
    {
        try {
            $status = $request['status'];
            DeliveryBoy::where('id', $id)->update(['status' => $status]);

            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
