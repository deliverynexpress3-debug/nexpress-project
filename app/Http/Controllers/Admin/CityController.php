<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class CityController extends Controller
{

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

                $query = City::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%");
                    });
                }

                $data = $query->get();


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $city_delete = __('label.delete_city');

                        $delete = ' <form onsubmit="return confirm(\'' . $city_delete . '\');" method="POST"  action="' . route('admin.city.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_city" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" >';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
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
            return view('admin.city.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',

            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $city_data = City::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($city_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_add_city')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_city')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $city_data = City::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($city_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_city')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_city')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = City::where('id', $id)->first();

            if (isset($data)) {
                $data->delete();
            }
            return redirect()->route('admin.city.index')->with('success',  __('label.city_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = City::where('id', $id)->first();
            if (isset($data)) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
