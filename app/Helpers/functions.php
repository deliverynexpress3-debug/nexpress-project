<?php

use App\Models\General_Setting;
use App\Models\SellerRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

// Setting
function Setting_Data()
{
    $setting = General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data;
}
function Login_Image()
{
    $Setting_Data = Setting_Data();

    $appName = Config::get('app.image_url');
    $login_page_img = $Setting_Data['app_logo'];
    $folder = "setting";

    if ($login_page_img != "" && Storage::disk('public')->exists($folder . '/' . $login_page_img)) {
        $login_page_img = $appName . $folder . '/' . $login_page_img;
    } else {
        $login_page_img = asset('assets/imgs/login.png');
    }
    return $login_page_img;
}
function App_Name()
{
    $data = Setting_Data();

    $app_name = $data['app_name'];
    if (isset($app_name) && $app_name != "") {
        return $app_name;
    } else {
        return env('APP_NAME');
    }
}
function Tab_Icon()
{
    $setting_data = Setting_Data();
    $app_logo = $setting_data['app_logo'];
    $folder = "setting";

    if ($app_logo != "" && $folder != "") {

        $img_base_url = Config::get('app.image_url');

        if (Storage::disk('public')->exists($folder . '/' . $app_logo)) {
            $app_logo = $img_base_url . $folder . '/' . $app_logo;
        } else {
            $app_logo = "";
        }
    } else {
        $app_logo = "";
    }

    return $app_logo;
}
function Tab_Icon_Path_For_PDF()
{
    $setting_data = Setting_Data();
    $app_logo = $setting_data['app_logo'];
    $folder = "setting";

    if ($app_logo != "" && $folder != "") {

        // Public disk path
        $storagePath = storage_path('app/public/' . $folder . '/' . $app_logo);

        if (file_exists($storagePath)) {
            return $storagePath;
        }
    }

    // Default image if not available
    return public_path('assets/imgs/dtshop.png');
}
function SellerData()
{
    if (Auth::guard('seller')->check()) {
        return Auth::guard('seller')->user();
    }

    return redirect()->route('seller.logout')->send();
}
function SellerInfo()
{
    $seller_id = SellerData()['id'];
    $data = SellerRequest::where('customer_id', $seller_id)->first();

    return $data;
}
// Basic
function Currency_Code()
{
    $setting = General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data['currency_code'];
}
function String_Cut($string, $len)
{
    if (strlen($string) > $len) {
        $string = mb_substr(strip_tags($string), 0, $len, 'utf-8') . '...';
    }
    return $string;
}
function No_Format($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
    return $num;
}
function Time_To_Milliseconds($str)
{

    $time = explode(":", $str);

    $hour = (int) $time[0] * 60 * 60 * 1000;
    $minute = (int) $time[1] * 60 * 1000;
    $sec = (int) $time[2] * 1000;
    $result = $hour + $minute + $sec;
    return $result;
}

// Demo Mode
function Demo_Mode()
{
    if (env('DEMO_MODE') == 'ON') {
        return 0;
    } else {
        return 1;
    }
}
function Demo_Domain()
{
    $domain = request()->getHost();
    if ($domain == base64_decode('bG9jYWxob3N0') || $domain == base64_decode('ODIuMjkuMTYxLjEx') ||    $domain == base64_decode('ZGV2LmRpdmluZXRlY2hzLmNvbQ==') || $domain == base64_decode('c3RhZy5kaXZpbmV0ZWNocy5jb20=') || $domain == base64_decode('ZHRiYXNrZXQuZGl2aW5ldGVjaHMuY29t')) {
        return 1;
    } else {
        return 0;
    }
}
function Item_Code()
{
    return base64_decode('NjEyNDc4MTQ=');
}
function Set_Environment_Value($envKey, $envValue)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    $oldValue = env($envKey);

    if (strpos($str, $envKey) !== false) {
        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
    } else {
        $str .= "{$envKey}={$envValue}\n";
    }

    $fp = fopen($envFile, 'w');
    fwrite($fp, $str);
    fclose($fp);
    return $envValue;
}
