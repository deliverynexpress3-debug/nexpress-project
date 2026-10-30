<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use App\Models\DailyNotification;
use Illuminate\Support\Facades\Validator;

class DailyNotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $type = $request['type'];

                if ($input_search != null && isset($input_search) && $input_search != "") {
                    $data = DailyNotification::where('title', 'LIKE', "%{$input_search}%")->where('type', $type)->orderBy('day', 'asc')->get();
                } else {
                    $data = DailyNotification::where('type', $type)->orderBy('day', 'asc')->get();
                }

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a class="edit-delete-btn edit_daily_notification" title="Edit" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '"data-title="' . $row->title . '"  data-message="' . $row->message . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.daily_notification.index');
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request,  $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'message' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            $requestData = $request->all();

            $requestData['title'] = $requestData['title'] ?? '';
            $requestData['message'] = $requestData['message'] ?? '';

            $daily_notification = DailyNotification::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($daily_notification->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.daily_notification_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.daily_notification_not_update')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
