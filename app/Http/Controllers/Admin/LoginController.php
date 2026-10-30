<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    private $folder = "setting";
    public $common;
    protected $redirectTo = 'admin/login';
    public function __construct()
    {
        try {
            $this->middleware('guest', ['except' => 'logout']);
            $this->common = new Common();
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function login(Request $request)
    {
        try {
            Auth()->guard('admin')->logout();

            $params['result'] = Setting_Data();
            if ($params['result']['panel_login_page_view'] == 1) {
                $params['result']['panel_login_page_bg_image'] = $this->common->getImage($this->folder, $params['result']['panel_login_page_bg_image']);
            } else if ($params['result']['panel_login_page_view'] == 2) {
                $params['result']['panel_login_page_image'] = $this->common->getImage($this->folder, $params['result']['panel_login_page_image']);
            }

            return view('admin.login.login', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function save_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:4',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            if (Auth()->guard('admin')->attempt(['email' => $requestData['email'], 'password' => $requestData['password']])) {
                return response()->json(array('status' => 200, 'success' => __('label.success_login')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_login')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function logout()
    {
        try {
            Auth()->guard('admin')->logout();
            return redirect()->route('admin.login')->with('success', __('label.logout_successfully'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
