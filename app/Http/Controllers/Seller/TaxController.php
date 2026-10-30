<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class TaxController extends Controller
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
                $input_type = $request['input_type'];

                $query = Taxes::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_type !== 'all') {
                    $query->where('type', $input_type);
                }
                $data = $query->get();


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $edit = __('label.edit');
                        $delete = __('label.delete');
                        $tax_delete = __('label.delete_taxes');

                        $delete = ' <form onsubmit="return confirm(\'' . $tax_delete . '\');" method="POST"  action="' . route('seller.tax.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="' . $delete . '"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="' . $edit . '">';
                        $btn .= '<a class="edit-delete-btn edit_taxes" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-type="' . $row->type . '" data-amount="' . $row->amount . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            $showLabel = __('label.active');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.inactive');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('seller.tax.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'amount' => 'required|numeric|min:1',

            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $tax_data = Taxes::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($tax_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.taxes_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.taxes_not_save')));
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
                'amount' => 'required|numeric|min:1',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $tax_data = Taxes::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($tax_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.taxes_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.taxes_not_update')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Taxes::where('id', $id)->first();

            if (isset($data)) {
                $data->delete();
            }
            return redirect()->route('seller.tax.index')->with('success',  __('label.controller.taxes_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = Taxes::where('id', $id)->first();
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
