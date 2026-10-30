<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\ZipCode;
use App\Models\Zones;
use App\Models\ZonesValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ZonesController extends Controller
{

    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['zipcode'] = ZipCode::where('status', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];

                $query = Zones::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('zone_name', 'LIKE', "%{$input_search}%");
                    });
                }

                $data = $query->get();


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $zones_delete = __('label.delete_zones');

                        $delete = ' <form onsubmit="return confirm(\'' . $zones_delete . '\');" method="POST"  action="' . route('admin.zones.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" ><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('admin.zones.edit', [$row->id]) . '" class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('option', function ($row) {
                        $optionvalue = ZonesValue::where('zone_id', $row->id)->with('zipcode')->get();

                        if (count($optionvalue) > 0) {
                            $data = '<div class="text-left">';

                            for ($i = 0; $i < count($optionvalue); $i++) {
                                if ($optionvalue[$i]->zipcode != null) {
                                    $ZipCode = $optionvalue[$i]->zipcode;
                                    $cityName = $this->common->GetCityByZipCode($ZipCode['id']);
                                    $data .= '<i class="fa-solid fa-arrow-right mr-2"></i> ' 
                                    . $ZipCode['area_name'] 
                                    . ' - ' 
                                    . $ZipCode['zipcode'] 
                                    . ' - ' 
                                    . $cityName 
                                    . '<br>';

                                }
                            }
                            $data .= '</div>';
                            return $data;
                        } else {
                            return '-';
                        }
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

                    ->rawColumns(['action', 'option', 'status'])
                    ->make(true);
            }
            return view('admin.zones.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {
            $params['zipcode'] = ZipCode::where('status', 1)->latest()->get();

            return view('admin.zones.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'zone_name' => 'required|min:2',
                'zipcode_id.*' => 'required|distinct',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            $zones = new Zones();
            $zones['zone_name'] = $request['zone_name'];
            if ($zones->save()) {

                $zipcodeId = $request['zipcode_id'];

                for ($i = 0; $i < count($zipcodeId); $i++) {

                    $insert = new ZonesValue();
                    $insert['zone_id'] = $zones['id'];
                    $insert['zipcode_id'] = $zipcodeId[$i];
                    $insert->save();
                }
                return response()->json(array('status' => 200, 'success' => __('label.success_add_zones')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_zones')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit($id)
    {
        try {

            $params['result'] = Zones::where('id', $id)->first();
            $params['zipcode'] = ZipCode::where('status', 1)->latest()->get();
            $params['zonevalue'] = ZonesValue::where('zone_id', $id)->where('status', 1)->get();
            if (isset($params['result']['id'])) {

                return view('admin.zones.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'zone_name' => 'required|min:2',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }
            $zones = Zones::where('id', $request['id'])->first();
            if (isset($zones['id'])) {

                $zones['zone_name'] = $request['zone_name'];
                if ($zones->save()) {

                    ZonesValue::where('zone_id', $id)->delete();

                    $zipcodeId = $request['zipcode_id'];

                    for ($i = 0; $i < count($zipcodeId); $i++) {
                        if (isset($zipcodeId[$i]) != null && isset($zipcodeId[$i]) != null) {

                            $insert = new ZonesValue();
                            $insert['zone_id'] = $zones['id'];
                            $insert['zipcode_id'] = $zipcodeId[$i];
                            $insert->save();
                        }
                    }


                    return response()->json(array('status' => 200, 'success' => __('label.success_edit_zones')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.error_edit_zones')));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_zones')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Zones::where('id', $id)->first();

            if (isset($data)) {
                $data->delete();
                ZonesValue::where('zone_id', $id)->delete();
            }
            return redirect()->route('admin.zones.index')->with('success',  __('label.zones_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = Zones::where('id', $id)->first();
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
