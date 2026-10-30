<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Hashtag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class HashtagController extends Controller
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

                $query = Hashtag::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_type !== 'all') {
                    $query->where('status', $input_type);
                }
                $data = $query->get();


                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $edit = __('label.edit');
                        $delete = __('label.delete');
                        $tag_delete = __('label.delete_tag');

                        $delete = ' <form onsubmit="return confirm(\'' . $tag_delete . '\');" method="POST"  action="' . route('seller.hashtag.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="' . $delete . '"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="' . $edit . '">';
                        $btn .= '<a class="edit-delete-btn edit_tag" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" >';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            $showLabel = __('label.show');
                            return "<a onclick=' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.hide');
                            return "<a onclick='style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
                    })
                    ->addColumn('total_used', function ($row) {
                        return "<strong class='primary-color'>$row->total_used</strong>";
                    })
                    ->rawColumns(['action', 'total_used', 'status'])
                    ->make(true);
            }
            return view('seller.hashtag.index', $params);
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
            $requestData['total_used'] = 0;
            $tag_data = Hashtag::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($tag_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.tag_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.tag_not_save')));
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

            $tag_data = Hashtag::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($tag_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.tag_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.tag_not_update')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Hashtag::where('id', $id)->first();

            if (isset($data)) {
                $data->delete();
            }
            return redirect()->route('seller.hashtag.index')->with('success',  __('label.controller.tag_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = Hashtag::where('id', $id)->first();
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
