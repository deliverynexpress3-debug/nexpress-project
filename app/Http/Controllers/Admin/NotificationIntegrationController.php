<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\NotificationConfiguration;
use Illuminate\Http\Request;
use Exception;


class NotificationIntegrationController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $getstatus = NotificationConfiguration::latest()->first();
            $params['main_status'] = $getstatus['status'];

            if ($request->ajax()) {

                $data = NotificationConfiguration::get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }

            return view('admin.notification_configuration.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function IntegrationSave(Request $request)
    {
        try {
            
            $entries = $request->input('entries');
            $type = $request->type;
            $status = $request->status;

            if ($type == "all") {
                if ($status == 1) {
                    NotificationConfiguration::query()->update([
                        'status' => 1,
                    ]);
                } elseif ($status == 0) {
                    NotificationConfiguration::query()->update([
                        'status' => 0,
                        'send_mail' => 0,
                        'send_notification' => 0
                    ]);
                }
            } else {
                foreach ($entries as $entry) {
                    $id = $entry['id'];
                    $array = [
                        'send_notification' => $entry['notification'],
                        'send_mail' => $entry['email'],
                    ];
                    NotificationConfiguration::where('id', $id)->update($array);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.setting_save_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
