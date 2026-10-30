<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\General_Setting;
use Illuminate\Http\Request;
use Exception;

class PermissionController extends Controller
{

    public function index()
    {
        try {

            $params['result'] = Setting_Data();
            if ($params['result']) {
                return view('admin.permission.index', $params);
            } else {
                return view('errors.404');
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {

            $data = $request->all();
            $data['brand_access'] = isset($data['brand_access']) ? $data['brand_access'] : 0;
            $data['category_access'] = isset($data['category_access']) ? $data['category_access'] : 0;
            $data['offer_access'] = isset($data['offer_access']) ? $data['offer_access'] : 0;
            $data['hashtag_access'] = isset($data['hashtag_access']) ? $data['hashtag_access'] : 0;
            $data['taxes_access'] = isset($data['taxes_access']) ? $data['taxes_access'] : 0;
            $data['stock_status_access'] = isset($data['stock_status_access']) ? $data['stock_status_access'] : 0;
            $data['attributes_access'] = isset($data['attributes_access']) ? $data['attributes_access'] : 0;
            $data['return_order_status_access'] = isset($data['return_order_status_access']) ? $data['return_order_status_access'] : 0;
            $data['order_status_access'] = isset($data['order_status_access']) ? $data['order_status_access'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }

            return response()->json(['status' => 200, 'success' => __('label.controller.setting_save_successfully')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
