<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\General_Setting;
use App\Models\Onboarding_Screen;
use App\Models\Smtp_Setting;
use App\Models\Social_Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class SettingController extends Controller
{
    private $folder = "app";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $params['result'] = Setting_Data();
            if ($params['result']) {

                $params['result']['app_logo'] = $this->common->getImage($this->folder, $params['result']['app_logo'], 'normal');

                $params['social_link'] = Social_Link::get();
                $this->common->imageNameToUrl($params['social_link'], 'image', $this->folder, 'normal');

                $params['onboarding_screen'] = Onboarding_Screen::get();
                $this->common->imageNameToUrl($params['onboarding_screen'], 'image', $this->folder, 'normal');

                $params['smtp'] = Smtp_Setting::latest()->first();

                return view('admin.setting.index', $params);
            } else {
                return redirect()->back()->with('error', __('label.controller.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function app(Request $request)
    {
        try {

            $data = $request->all();
            $data["app_name"] = isset($data['app_name']) ? $data['app_name'] : '';
            $data["host_email"] = isset($data['host_email']) ? $data['host_email'] : '';
            $data["app_version"] = isset($data['app_version']) ? $data['app_version'] : '';
            $data["author"] = isset($data['author']) ? $data['author'] : '';
            $data["email"] = isset($data['email']) ? $data['email'] : '';
            $data["contact"] = isset($data['contact']) ? $data['contact'] : '';
            $data["app_desripation"] = isset($data['app_desripation']) ? $data['app_desripation'] : '';
            $data["website"] = isset($data['website']) ? $data['website'] : '';

            if (isset($data['app_logo'])) {
                $files = $data['app_logo'];
                $data['app_logo'] = $this->common->saveImage($files, $this->folder, 'app_');
                $this->common->deleteImageToFolder($this->folder, basename($data['old_app_logo']));
            }

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveTmdbKey(Request $request)
    {
        try {

            if ($request['tmdb_status'] == 1) {
                $validator = Validator::make($request->all(), [
                    'tmdb_api_key' => 'required',
                ]);
                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }
            }

            $data = $request->all();
            $data["tmdb_status"] = isset($data['tmdb_status']) ? $data['tmdb_status'] : 0;
            $data["tmdb_api_key"] = isset($data['tmdb_api_key']) ? $data['tmdb_api_key'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function currency(Request $request)
    {
        try {

            $data = $request->all();
            $data["currency"] = isset($data['currency']) ? strtoupper($data['currency']) : '';
            $data["currency_code"] = isset($data['currency_code']) ? $data['currency_code'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveBasicConfigrations(Request $request)
    {
        try {

            if ($request['multiple_device_sync'] == 1) {

                $validator = Validator::make($request->all(), [
                    'no_of_device_sync' => 'required|numeric|min:1',
                ]);
                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(array('status' => 400, 'errors' => $errs));
                }
            }

            $data = $request->all();
            $data["auto_play_trailer"] = isset($data['auto_play_trailer']) ? $data['auto_play_trailer'] : 1;
            $data["active_tv_status"] = isset($data['active_tv_status']) ? $data['active_tv_status'] : 1;
            $data["parent_control_status"] = isset($data['parent_control_status']) ? $data['parent_control_status'] : 1;
            $data["watchlist_status"] = isset($data['watchlist_status']) ? $data['watchlist_status'] : 1;
            $data["download_status"] = isset($data['download_status']) ? $data['download_status'] : 1;
            $data["continue_watching_status"] = isset($data['continue_watching_status']) ? $data['continue_watching_status'] : 1;
            $data["subscription_status"] = isset($data['subscription_status']) ? $data['subscription_status'] : 1;
            $data["coupon_status"] = isset($data['coupon_status']) ? $data['coupon_status'] : 1;
            $data["rent_status"] = isset($data['rent_status']) ? $data['rent_status'] : 1;
            $data["multiple_device_sync"] = isset($data['multiple_device_sync']) ? $data['multiple_device_sync'] : 0;
            $data["no_of_device_sync"] = isset($data['no_of_device_sync']) ? $data['no_of_device_sync'] : 0;
            $data["on_boarding_screen_status"] = isset($data['on_boarding_screen_status']) ? $data['on_boarding_screen_status'] : 1;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function smtpSave(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'host' => 'required',
                'port' => 'required',
                'protocol' => 'required',
                'user' => 'required',
                'pass' => 'required',
                'from_name' => 'required',
                'from_email' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if (isset($request->id) && $request->id != null && $request->id != "") {

                $smtp = Smtp_Setting::where('id', $request->id)->first();
                if (isset($smtp->id)) {
                    $smtp->protocol = $request->protocol;
                    $smtp->host = $request->host;
                    $smtp->port = $request->port;
                    $smtp->user = $request->user;
                    $smtp->pass = $request->pass;
                    $smtp->from_name = $request->from_name;
                    $smtp->from_email = $request->from_email;
                    $smtp->status = $request->status;
                    if ($smtp->save()) {
                        return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('label.controller.data_not_updated')));
                    }
                }
            } else {

                $insert = new Smtp_Setting();
                $insert->protocol = $request->protocol;
                $insert->host = $request->host;
                $insert->port = $request->port;
                $insert->user = $request->user;
                $insert->pass = $request->pass;
                $insert->from_name = $request->from_name;
                $insert->from_email = $request->from_email;
                $insert->status = $request->status;
                if ($insert->save()) {

                    $this->common->SetSmtpConfig();
                    return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.controller.data_not_updated')));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveSocialLink(Request $request)
    {
        try {

            $arr_name = $request['name'];
            $arr_url = $request['url'];
            $arr_img = $request->file('image');
            $arr_old_image = $request['old_image'];

            // Save New All Link
            $not_delete_img = array();
            $not_delete_ids = array();

            for ($i = 0; $i < count($arr_name); $i++) {

                if (!empty($arr_name[$i]) && !empty($arr_url[$i])) {

                    if (!empty($arr_img[$i])) {

                        $insert = new Social_Link();
                        $insert->name = $arr_name[$i];
                        $insert->url = $arr_url[$i];
                        $insert->image = $this->common->saveImage($arr_img[$i], $this->folder, 'soc_link_');
                        $insert->save();

                        $this->common->deleteImageToFolder($this->folder, $arr_old_image[$i]);
                    } else {
                        if (!empty($arr_old_image[$i])) {

                            $insert = new Social_Link();
                            $insert->name = $arr_name[$i];
                            $insert->url = $arr_url[$i];
                            $insert->image = $arr_old_image[$i];
                            $insert->save();
                            $not_delete_img[] = $arr_old_image[$i];
                        }
                    }
                    $not_delete_ids[] = $insert->id;
                }
            }

            // Delete Old All Link 
            $all_old_link = Social_Link::whereNotIn('id', $not_delete_ids)->get();
            for ($i = 0; $i < count($all_old_link); $i++) {

                if (!in_array($all_old_link[$i]['image'], $not_delete_img)) {

                    $this->common->deleteImageToFolder($this->folder, $all_old_link[$i]['image']);
                }

                $all_old_link[$i]->delete();
            }

            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function saveOnBoardingScreen(Request $request)
    {
        try {

            $arr_title = $request['title'];
            $arr_img = $request->file('image');
            $arr_old_image = $request['old_image'];

            // Save New All Link
            $not_delete_img = array();
            $not_delete_ids = array();

            for ($i = 0; $i < count($arr_title); $i++) {

                if (!empty($arr_title[$i])) {

                    if (!empty($arr_img[$i])) {

                        $insert = new Onboarding_Screen();
                        $insert->title = $arr_title[$i];
                        $insert->image = $this->common->saveImage($arr_img[$i], $this->folder, 'on_board_');
                        $insert->save();

                        $this->common->deleteImageToFolder($this->folder, $arr_old_image[$i]);
                    } else {
                        if (!empty($arr_old_image[$i])) {

                            $insert = new Onboarding_Screen();
                            $insert->title = $arr_title[$i];
                            $insert->image = $arr_old_image[$i];
                            $insert->save();
                            $not_delete_img[] = $arr_old_image[$i];
                        }
                    }
                    $not_delete_ids[] = $insert->id;
                }
            }

            // Delete Old Data
            $all_old_data = Onboarding_Screen::whereNotIn('id', $not_delete_ids)->get();
            for ($i = 0; $i < count($all_old_data); $i++) {

                if (!in_array($all_old_data[$i]['image'], $not_delete_img)) {

                    $this->common->deleteImageToFolder($this->folder, $all_old_data[$i]['image']);
                }
                $all_old_data[$i]->delete();
            }

            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function vapIdKey(Request $request)
    {
        try {

            $data = $request->all();
            $data["vapid_key"] = isset($data['vapid_key']) ? $data['vapid_key'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function firebaseProjectId(Request $request)
    {
        try {

            $data = $request->all();
            $data['firebase_project_id'] = isset($data['firebase_project_id']) ? $data['firebase_project_id'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.setting_save')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
