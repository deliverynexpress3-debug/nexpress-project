<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Common;
use App\Models\Category;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class AttributesController extends Controller
{
    private $folder = "attributes";
    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_industires = $request['input_industires_id'];
                $input_type = $request['input_type'];

                $query = Attributes::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('title', 'LIKE', "%{$input_search}%");
                    });
                }

                if ($input_industires != "all" && isset($input_industires) != null) {
                    $query->where('industries_id', $input_industires);
                }
                if ($input_type != "all" && isset($input_type) != null) {
                    $query->where('field_type', $input_type);
                }
                $data = $query->get();
                $this->common->imageNameToUrl($data, 'icon', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $attributes_delete = __('label.delete_attributes');

                        $delete = '<form onsubmit="return confirm(\'' . $attributes_delete . '\');" method="POST"  action="' . route('admin.attributes.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a class="edit-delete-btn" title="Edit" href="' . route('admin.attributes.edit', [$row->id]) . '">';
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
                    ->addColumn('option', function ($row) {
                        $optionvalue = AttributesOption::where('attributes_id', $row->id)->get();
                        if (count($optionvalue) > 0) {
                            $data = '<div class="text-left">';
                            for ($i = 0; $i < count($optionvalue); $i++) {
                                $data .= '<i class="fa-solid fa-arrow-right mr-2"></i>' . $optionvalue[$i]['option'] . '<br>';
                            }
                            $data .= '</div>';
                            return $data;
                        } else {
                            return '-';
                        }
                    })
                    ->rawColumns(['action', 'status', 'option'])
                    ->make(true);
            }

            return view('admin.attributes.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function create()
    {
        try {
            $params['category'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get()->toArray();

            return view('admin.attributes.add', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            if ($request->field_type == 1 || $request->field_type == 2 || $request->field_type == 6) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                ]);
            } elseif ($request->field_type == 3 || $request->field_type == 4 || $request->field_type == 5) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'option.*' => 'required',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'field_type' => 'required',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:10240',
                ]);
            }
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();

            $category_id = implode(",", $requestData['category_id']);
            $requestData['category_id'] = $category_id;

            $new_value = $requestData['option'];

            if (isset($requestData['icon']) && $requestData['icon'] != 'undefined') {
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'attributes_');
            } else {
                $requestData['icon'] = "";
            }

            unset($requestData['option'], $requestData['_token']);

            $attributes_id = Attributes::insertGetId($requestData);
            if (isset($attributes_id) && $attributes_id != null) {

                $Get_Value = Attributes::where('id', $attributes_id)->first();
                if ($Get_Value['field_type'] == 3 || $Get_Value['field_type'] == 4 || $Get_Value['field_type'] == 5) {

                    AttributesOption::where('attributes_id', $attributes_id)->delete();
                    for ($i = 0; $i < count($new_value); $i++) {
                        $insert['attributes_id'] = $attributes_id;
                        $insert['option'] = $new_value[$i];
                        AttributesOption::insert($insert);
                    }
                }
                return response()->json(array('status' => 200, 'success' => __('label.success_add_attributes')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_attributes')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function edit(Request $request, $id)
    {
        try {

            $params['category'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get()->toArray();

            $params['result'] = Attributes::where('id', $id)->first();
            $params['attributes_option'] = AttributesOption::where('attributes_id', $id)->get();

            $this->common->imageNameToUrl(array($params['result']), 'icon', $this->folder);

            if ($request->ajax()) {
                $input_search = $request['input_search'];

                if ($input_search != null && isset($input_search)) {
                    $amenity = AttributesOption::where('attributes_id', $id)->where('value', 'LIKE', "%{$input_search}%")->orderBy('id', 'desc')->latest()->get();
                } else {
                    $amenity = AttributesOption::where('attributes_id', $id)->orderBy('id', 'desc')->get();
                }

                return DataTables()::of($amenity)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $value_delete = __('label.delete_attributes_value');

                        $delete = ' <form onsubmit="return confirm(\'' . $value_delete . '\');" method="POST"  action="">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_value" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-value="' . $row->value . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })

                    ->rawColumns(['action'])
                    ->make(true);
            }

            if ($params['result'] != null) {
                return view('admin.attributes.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request)
    {
        try {
            if ($request->field_type == 1 || $request->field_type == 2 || $request->field_type == 6) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                ]);
            } elseif ($request->field_type == 3 || $request->field_type == 4 || $request->field_type == 5) {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    // 'option.*' => 'required',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                ]);
            } else {
                $validator = Validator::make($request->all(), [
                    'title' => 'required|min:2',
                    'field_type' => 'required',
                    'category_id' => 'required',
                    'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                ]);
            }
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }


            $requestData = $request->all();

            $category_id = implode(",", $requestData['category_id']);
            $requestData['category_id'] = $category_id;

            $new_value = $requestData['option'];

            if (isset($requestData['icon']) && $requestData['icon'] != 'undefined') {
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'attributes_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_icon']));
            }

            unset($requestData['option'], $requestData['old_icon']);


            $data = Attributes::updateOrCreate(['id' => $requestData['id']], $requestData);
            $attributes_id = $data['id'];
            if (isset($attributes_id) && $attributes_id != null) {
                if ($data['field_type'] == 3 || $data['field_type'] == 4 || $data['field_type'] == 5) {

                    AttributesOption::where('attributes_id', $attributes_id)->delete();

                    for ($i = 0; $i < count($new_value); $i++) {
                        if (isset($new_value[$i]) != null && isset($new_value[$i]) != null) {
                            $insert['attributes_id'] = $attributes_id;
                            $insert['option'] = $new_value[$i];
                            AttributesOption::insert($insert);
                        }
                    }
                }
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_attributes')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_attributes')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Attributes::where('id', $id)->first();
            if (isset($data)) {

                $this->common->deleteImageToFolder($this->folder, $data['icon']);
                $data->delete();

                Attributes::DeleteAllData($id);
            }
            return redirect()->route('admin.attributes.index')->with('success', __('label.attributes_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {
            $data = Attributes::where('id', $id)->first();
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
