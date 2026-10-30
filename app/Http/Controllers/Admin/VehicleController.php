<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Common;
use App\Models\DeliveryAssignment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class VehicleController extends Controller
{

    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['brand'] = Brand::whereIn('type', ['all', 2])->where('status', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_brand = $request['input_brand_id'];

                $query = Vehicle::with('brand')->latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%")->orWhere('number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_brand != "all") {
                    $query->where('brand_id', $input_brand);
                }
                $data = $query->get();


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $vehicle_delete = __('label.delete_vehicle');

                        $delete = ' <form onsubmit="return confirm(\'' . $vehicle_delete . '\');" method="POST"  action="' . route('admin.vehicle.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_vehicle" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '"  data-number="' . $row->number . '" data-brand_id="' . $row->brand_id . '">';
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
            return view('admin.vehicle.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'number' => 'required|min:2',
                'brand_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $requestData['is_used'] = 0;

            $vehicle_data = Vehicle::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($vehicle_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_add_vehicle')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_vehicle')));
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
                'number' => 'required|min:2',
                'brand_id' => 'required',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $vehicle_data = Vehicle::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($vehicle_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_vehicle')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_vehicle')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Vehicle::where('id', $id)->first();

            if (isset($data)) {
                $vehicle = DeliveryAssignment::where('vehicle_id', $id)->first();
                if (isset($vehicle) && $vehicle != null) {
                    return redirect()->back()->with('error', __('label.vehicle_not_delete'));
                } else {
                    $data->delete();
                    return redirect()->route('admin.vehicle.index')->with('success',  __('label.vehicle_delete'));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = Vehicle::where('id', $id)->first();
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
