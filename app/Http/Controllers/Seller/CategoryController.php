<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Category;
use App\Models\Common;
use App\Models\Items;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    private $folder = "category";
    private $folder_attributes = "attributes";
    public $common;

    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['categories'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get();
            $params['sort_category'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];

                $query = Category::where('parent_category_id', 0)->orderBy('sort_order', 'asc')->latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%");
                    });
                }
                $data = $query->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $edit = __('label.edit');
                        $delete = __('label.delete');
                        $category_delete = __('label.delete_category');

                        $delete = ' <form onsubmit="return confirm(\'' . $category_delete . '\');" method="POST"  action="' . route('seller.category.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="' . $delete . '"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="' . $edit . '">';
                        $btn .= '<a class="edit-delete-btn edit_category" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-parent_category_id="' . $row->parent_category_id . '" data-name="' . $row->name . '" data-image="' . $row->image . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            $showLabel = __('label.show');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.hide');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
                    })
                    ->addColumn('attributes', function ($row) {
                        $get_amenity = Attributes::get();
                        $attributes = __('label.attributes');


                        if (!empty(sizeof($get_amenity))) {
                            $array = [];

                            foreach ($get_amenity as $item) {
                                $category_id = explode(",", $item->category_id);
                                if (in_array($row->id, $category_id)) {
                                    $array[] = $item->id;
                                }
                            }
                            $amenity = Attributes::whereIn('id', $array)->latest()->count();
                            if ($amenity > 0) {
                                $count = (string)$amenity  . ' ' . $attributes;
                                $title = '<a href="' . route("seller.category.attributes.index", $row->id) . '" class="btn-default">' . $count . '</a>';
                                return $title;
                            } else {
                                return "-";
                            }
                        } else {
                            return "-";
                        }
                    })
                    ->addColumn('sub_category', function ($row) {
                        $sub_category = Category::where('parent_category_id', $row->id)->count();
                        if ($sub_category > 0) {

                            $sub_category = (string)$sub_category  . ' ' . 'Sub Categories';
                            $title = '<a href="' . route('seller.subcategory_list', [$row->id]) . '" class="btn-default">' . $sub_category . '</a>';
                            return $title;
                        } else {
                            return "-";
                        }
                    })
                    ->rawColumns(['action', 'status', 'attributes', 'sub_category'])
                    ->make(true);
            }
            return view('seller.category.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:tbl_category,name',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'category_');
            }
            $requestData['parent_category_id'] = isset($request->parent_category_id) ? $request->parent_category_id : 0;
            $requestData['status'] = 0;

            $category_data = Category::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($category_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.category_save')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.category_not_save')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:tbl_category,name,' . $request->id,
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $requestData = $request->all();
            $requestData['parent_category_id'] = $request->parent_category_id != null ? $request->parent_category_id : 0;
            if (isset($requestData['image'])) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'category_');

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            unset($requestData['old_image']);

            $category_data = Category::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($category_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.controller.category_update')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.category_not_update')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id)
    {
        try {

            $data = Category::where('id', $id)->first();
            if (isset($data)) {
                Category::where('parent_category_id', $id)->get()->each(function ($child) use ($data) {
                    $child->status = $data->status === 1 ? 0 : 1;
                    $child->save();
                });

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
    public function destroy($id)
    {
        try {
            $data = Category::where('id', $id)->first();
            if (isset($data)) {
                $item = Items::where('category_id', $id)->first();
                if (isset($item) && $item != null) {
                    return redirect()->back()->with('error', __('label.controller.category_not_delete'));
                } else {
                    Category::where('parent_category_id', $data['id'])->update(['parent_category_id' => 0]);
                    $this->common->deleteImageToFolder($this->folder, $data['image']);
                    $data->delete();

                    return redirect()->back()->with('success', __('label.controller.category_delete'));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    //amenity Field
    public function AttributesIndex(Request $request, $id)
    {
        try {
            $get_amenity = Attributes::get();
            $array = [];
            foreach ($get_amenity as $item) {
                $category_id = explode(",", $item->category_id);
                if (in_array($id, $category_id)) {
                    $array[] = $item->id;
                }
            }
            $params['category_id'] = $id;

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];

                $query = Attributes::whereIn('id', $array)->latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('title', 'LIKE', "%{$input_search}%");
                    });
                }

                if ($input_type != "all" && isset($input_type) != null) {
                    $query->where('field_type', $input_type);
                }
                $data = $query->get();

                $this->common->imageNameToUrl($data, 'icon', $this->folder_attributes);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $attributes_delete = __('label.delete_attributes');

                        $delete = '<form onsubmit="return confirm(\'' . $attributes_delete . '\');" method="POST"  action="' . route('seller.attributes.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="Delete"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="Edit">';
                        $btn .= '<a class="edit-delete-btn" title="Edit" href="' . route('seller.attributes.edit', [$row->id]) . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            $showLabel = __('label.show');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.hide');
                            return "<a id='$row->id' style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
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

            return view('seller.category.attributes_index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function subcategory_list(Request $request, $id)
    {
        try {

            $params['data'] = [];
            $params['category'] = Category::where('id', $id)->first();
            $params['sort_category'] = Category::where('parent_category_id', $id)->where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];

                if ($input_search != null && isset($input_search)) {
                    $data = Category::where('parent_category_id', $id)->where('name', 'LIKE', "%{$input_search}%")->where('parent_category_id', '!=', 0)->orderBy('sort_order', 'asc')->latest()->get();
                } else {
                    $data = Category::where('parent_category_id', $id)->where('parent_category_id', '!=', 0)->orderBy('sort_order', 'asc')->get();
                }

                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $edit = __('label.edit');
                        $delete = __('label.delete');
                        $category_delete = __('label.delete_category');

                        $delete = ' <form onsubmit="return confirm(\'' . $category_delete . '\');" method="POST"  action="' . route('seller.category.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="' . $delete . '"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around" title="' . $edit . '">';
                        $btn .= '<a class="edit-delete-btn edit_category" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-parent_category_id="' . $row->parent_category_id . '" data-name="' . $row->name . '" data-industries_id="' . $row->industries_id . '" data-image="' . $row->image . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            $showLabel = __('label.show');
                            return "<a onclick=' id='$row->id' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.hide');
                            return "<a onclick='id='$row->id' style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
                    })
                    ->addColumn('attributes', function ($row) {
                        $get_amenity = Attributes::get();
                        $attributes = __('label.attributes');
                        if (!empty(sizeof($get_amenity))) {
                            $array = [];

                            foreach ($get_amenity as $item) {
                                $category_id = explode(",", $item->category_id);
                                if (in_array($row->id, $category_id)) {
                                    $array[] = $item->id;
                                }
                            }
                            $amenity = Attributes::whereIn('id', $array)->latest()->count();

                            $count = (string)$amenity  . ' ' . $attributes;
                            $title = '<a href="' . route("seller.category.attributes.index", $row->id) . '" class="btn-default">' . $count . '</a>';
                            return $title;
                        } else {
                            $count = 0  . ' ' . $attributes;
                            $title = '<a href="' . route("seller.category.attributes.index", $row->id) . '" class="btn-default">' . $count . '</a>';
                            return $title;
                        }
                    })
                    ->addColumn('sub_category', function ($row) {
                        $sub_category = Category::where('parent_category_id', $row->id)->count();
                        if ($sub_category > 0) {

                            $sub_category = (string)$sub_category  . ' ' . 'Sub Categories';
                            $title = '<a href="' . route('seller.subcategory_list', [$row->id]) . '" class="btn-default">' . $sub_category . '</a>';
                            return $title;
                        } else {
                            return "-";
                        }
                    })
                    ->rawColumns(['action', 'status', 'attributes', 'sub_category'])
                    ->make(true);
            }
            return view('seller.category.sub_category', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function CategorySortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Category::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(array('status' => 200, 'success' => __('label.controller.data_edit_successfully')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function ChildChangeStatus($id)
    {
        try {

            $data = Category::where('id', $id)->first();
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
