<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
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
            $seller = SellerData();
            if (isset($seller) && isset($seller['id'])) {
                $params['result'] = Customer::where('id', $seller['id'])->first();
                $params['otherData'] = SellerRequest::where('customer_id', $seller['id'])->first();

                $this->common->imageNameToUrl(array($params['result']), 'image', $this->folder);
                $this->common->imageNameToUrl(array($params['otherData']), 'store_logo', $this->folder);


                return view('seller.profile.index', $params);
            } else {
                return redirect()->route('seller.logout');
            }

            return view('seller.profile.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_customer,email,' . $id,
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    Rule::unique('tbl_customer')->where(function ($query) use ($request, $id) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number)
                            ->where('id', '!=', $id);
                    }),
                ],
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
                'bank_proof' => 'image|mimes:jpeg,png,jpg|max:5120',
                'store_logo' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }
           
            $seller = Customer::where('id', $request['id'])->first();

            if (isset($seller['id'])) {

                if (isset($request['image'])) {
                    $files = $request['image'];
                    $seller['image'] = $this->common->saveImage($files, $this->folder, 'seller_');
                    $this->common->deleteImageToFolder($this->folder, basename($request->old_image));
                }

                $seller['full_name'] = $request['full_name'];
                $seller['email'] = $request['email'];
                if ($request['password'] != null && isset($request['password'])) {
                    $seller['password'] = Hash::make($request['password']);
                }

                $seller['country_code'] = $request['country_code'];
                $seller['mobile_number'] = $request['mobile_number'];
                $seller['country_name'] = $request['country_name'];
                if ($seller->save()) {

                    $otherInfo = SellerRequest::where('customer_id', $request['id'])->first();

                    $otherInfo['store_name'] = $request['store_name'];
                    $otherInfo['store_url'] = $request['store_url'] ?? "";
                    $otherInfo['store_address'] = $request['store_address'] ?? "";


                    if (isset($request['store_logo'])) {
                        $files2 = $request['store_logo'];
                        $otherInfo['store_logo'] = $this->common->saveImage($files2, $this->folder, 'store_');
                        $this->common->deleteImageToFolder($this->folder, basename($request->old_store_logo));
                    }

                    if ($otherInfo->save()) {
                        return response()->json(array('status' => 200, 'success' => __('label.controller.seller_profile_update')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('label.controller.seller_not_update')));
                    }
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.controller.seller_not_update')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.controller.seller_not_update')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ChangePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:4',
                'confirm_password' => 'required|min:4|same:new_password',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $super_admin = Customer::where('id', $request['id'])->first();
            if (isset($super_admin) && $super_admin != null) {

                if (Hash::check($request['current_password'], $super_admin['password'])) {

                    $super_admin['password'] = Hash::make($request['new_password']);
                    if ($super_admin->save()) {
                        return response()->json(['status' => 200, 'success' => __('label.controller.password_change_successfully')]);
                    }
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.controller.please_enter_right_current_password')]);
                }
            } else {
                return redirect()->route('seller.logout');
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
