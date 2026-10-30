<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Section;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class SectionController extends Controller
{
    private $folder = "section";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {
            $params['data'] = [];
            $params['type'] = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();

            $params['categories'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get();
            $params['brand'] = Brand::where('status', 1)->latest()->get();
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();

            return view('admin.section.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {

        $rules = [
            'is_home_screen' => 'required',
            'top_type_id' => 'required',
            'content_type' => 'required',
            'title' => 'required',
            'screen_layout' => 'required',
        ];
        if ($request->content_type == 2) {
            $rules['product_content_type'] = 'required';
        }
        if ($request->content_type == 1) {
            $rules['category'] = 'required';
            $rules['no_of_content'] = 'required|min:1';
        }
        if ($request->background_type == 'color') {
            $rules['bg_color'] = 'required';
        } else {
            $rules['bg_image'] = 'required|image|mimes:jpeg,png,jpg|max:5120';
        }

        if ($request->product_content_type == 5) {
            $rules['include_low_price'] = 'required|numeric|min:1';
            $rules['include_high_price'] = 'required|numeric|min:1';
        }
        if ($request->product_content_type == 1) {
            if ($request['section_type'] == 1) {
                $rules['content'] = 'required';
            }
        }
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->all()
            ]);
        }
        $requestData = $request->all();

        $requestData['type_id'] = $requestData['top_type_id'];
        $requestData['short_title'] = isset($request->short_title) ? $request->short_title : '';
        $requestData['background_type'] = $requestData['background_type'];
        if ($requestData['background_type'] == 'image') {
            if (isset($requestData['bg_image'])) {
                $files = $requestData['bg_image'];
                $requestData['background_value'] = $this->common->saveImage($files, $this->folder, 'bg_');
            }
        } else {
            $requestData['background_value'] = $requestData['bg_color'];
        }

        if ($requestData['content_type'] == 1) {

            $requestData['category_id'] = $request['category'] != null ? implode(',', $request['category']) : "";
            $requestData['content_ids'] = "";
            $requestData['sub_category_id'] = 0;
            $requestData['brand_id'] =  0;
            $requestData['seller_id'] = 0;
            $requestData['product_content_type'] = 0;
            $requestData['selling_type'] =  0;
            $requestData['include_low_price'] =  0;
            $requestData['include_high_price'] =  0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_like'] = 0;
            $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 1;
            $requestData['no_of_row'] = isset($request->no_of_row) ? $request->no_of_row : 1;
            $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            $requestData['load_more'] = isset($request->load_more) ? $request->load_more : 0;
            $requestData['sortable'] = 1;
            $requestData['status'] = 1;
        } elseif ($requestData['content_type'] == 2) {
            if ($requestData['product_content_type'] == 1) {
                if ($requestData['section_type'] == 1) {
                    $requestData['content_ids'] = $request['content'] != null ? implode(',', $request['content']) : "";
                } else {
                    $requestData['content_ids'] = "";
                }
            } else {
                $requestData['content_ids'] = "";
            }

            $requestData['category_id'] = isset($request->category_id) ? $request->category_id : "";
            $requestData['product_content_type'] = isset($request->product_content_type) ? $request->product_content_type : 1;
            $requestData['selling_type'] = isset($request->selling_type) ? $request->selling_type : 1;
            $requestData['sub_category_id'] = isset($request->sub_category_id) ? $request->sub_category_id : 0;
            $requestData['brand_id'] = isset($request->brand_id) ? $request->brand_id : 0;
            $requestData['seller_id'] = isset($request->seller_id) ? $request->seller_id : 0;
            $requestData['include_low_price'] = isset($request->include_low_price) ? $request->include_low_price : 0;
            $requestData['include_high_price'] = isset($request->include_high_price) ? $request->include_high_price : 0;
            $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
            $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
            $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 1;
            $requestData['no_of_row'] = isset($request->no_of_row) ? $request->no_of_row : 1;
            $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            $requestData['load_more'] = isset($request->load_more) ? $request->load_more : 0;
        }

        unset($requestData['top_type_id'], $requestData['content'], $requestData['category'], $requestData['bg_color'], $requestData['bg_image']);

        $section_data = Section::updateOrCreate(['id' => $requestData['id']], $requestData);
        if (isset($section_data->id)) {
            return response()->json(array('status' => 200, 'success' => __('label.success_add_section')));
        } else {
            return response()->json(array('status' => 400, 'errors' => __('label.error_add_section')));
        }
    }
    public function update(Request $request)
    {
        $rules = [
            'is_home_screen' => 'required',
            'top_type_id' => 'required',
            'content_type' => 'required',
            'title' => 'required',
            'screen_layout' => 'required',
        ];
        if ($request->content_type == 2) {
            $rules['product_content_type'] = 'required';
        }
        if ($request->content_type == 1) {
            $rules['category'] = 'required';
            $rules['no_of_content'] = 'required|min:1';
        }
        if ($request->background_type == 'color') {
            $rules['bg_color'] = 'required';
        } else {
            $rules['bg_image'] = 'image|mimes:jpeg,png,jpg|max:5120';
        }

        if ($request->product_content_type == 5) {
            $rules['include_low_price'] = 'required|numeric|min:1';
            $rules['include_high_price'] = 'required|numeric|min:1';
        }
        if ($request->product_content_type == 1) {
            if ($request['section_type'] == 1) {
                $rules['content'] = 'required';
            }
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->all()
            ]);
        }

        $requestData = $request->all();

        $requestData['type_id'] = $requestData['top_type_id'];
        $requestData['short_title'] = isset($request->short_title) ? $request->short_title : '';
        $requestData['background_type'] = $requestData['background_type'];

        if ($requestData['background_type'] == 'image') {
            if (isset($requestData['bg_image'])) {
                $files = $requestData['bg_image'];
                $requestData['background_value'] = $this->common->saveImage($files, $this->folder, 'bg_');

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_bg_image']));
            }
            unset($requestData['old_bg_image']);
        } else {
            $requestData['background_value'] = $requestData['bg_color'];
        }

        if ($requestData['content_type'] == 1) {

            $requestData['category_id'] = $request['category'] != null ? implode(',', $request['category']) : "";
            $requestData['content_ids'] = "";
            $requestData['sub_category_id'] = 0;
            $requestData['brand_id'] =  0;
            $requestData['seller_id'] = 0;
            $requestData['product_content_type'] = 0;
            $requestData['selling_type'] =  0;
            $requestData['include_low_price'] =  0;
            $requestData['include_high_price'] =  0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_like'] = 0;
            $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 1;
            $requestData['no_of_row'] = isset($request->no_of_row) ? $request->no_of_row : 1;
            $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            $requestData['load_more'] = isset($request->load_more) ? $request->load_more : 0;
        } elseif ($requestData['content_type'] == 2) {
            if ($requestData['product_content_type'] == 1) {
                if ($requestData['section_type'] == 1) {
                    $requestData['content_ids'] = $request['content'] != null ? implode(',', $request['content']) : "";
                } else {
                    $requestData['content_ids'] = "";
                }
            } else {
                $requestData['content_ids'] = "";
            }

            $requestData['category_id'] = isset($request->category_id) ? $request->category_id : "";
            $requestData['product_content_type'] = isset($request->product_content_type) ? $request->product_content_type : 1;
            $requestData['selling_type'] = isset($request->selling_type) ? $request->selling_type : 1;
            $requestData['sub_category_id'] = isset($request->sub_category_id) ? $request->sub_category_id : 0;
            $requestData['brand_id'] = isset($request->brand_id) ? $request->brand_id : 0;
            $requestData['seller_id'] = isset($request->seller_id) ? $request->seller_id : 0;
            $requestData['include_low_price'] = isset($request->include_low_price) ? $request->include_low_price : 0;
            $requestData['include_high_price'] = isset($request->include_high_price) ? $request->include_high_price : 0;
            $requestData['order_by_upload'] = isset($request->order_by_upload) ? $request->order_by_upload : 0;
            $requestData['order_by_like'] = isset($request->order_by_like) ? $request->order_by_like : 0;
            $requestData['no_of_content'] = isset($request->no_of_content) ? $request->no_of_content : 1;
            $requestData['no_of_row'] = isset($request->no_of_row) ? $request->no_of_row : 1;
            $requestData['view_all'] = isset($request->view_all) ? $request->view_all : 0;
            $requestData['load_more'] = isset($request->load_more) ? $request->load_more : 0;
        }

        unset($requestData['top_type_id'], $requestData['content'], $requestData['category'], $requestData['bg_image'], $requestData['bg_color'], $requestData['old_bg_image']);

        $section_data = Section::updateOrCreate(['id' => $requestData['id']], $requestData);
        if (isset($section_data->id)) {
            return response()->json(array('status' => 200, 'success' => __('label.success_edit_section')));
        } else {
            return response()->json(array('status' => 400, 'errors' => __('label.error_edit_section')));
        }
    }
    public function show($id)
    {
        try {
            $data = Section::where('id', $id)->first();
            if (isset($data)) {

                if ($data['background_type'] == 'image') {
                    $this->common->deleteImageToFolder($this->folder, $data['background_type']);
                }
                $data->delete();

                return response()->json(array('status' => 200, 'success' => __('label.section_delete')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function GetSectionData(Request $request)
    {
        try {
            if ($request['is_home_screen'] == 1) {
                $data = Section::where('is_home_screen', 1)->orderBy('sortable', 'asc')->latest()->get();
                foreach ($data as $value) {
                    if ($value['content_type'] == 1) {

                        $category =  explode(',', $value['category_id']);

                        $category_name = [];

                        for ($i = 0; $i < count($category); $i++) {
                            $item = Category::where('id', $category[$i])->first();
                            $category_name[] = isset($item->id) ? $item->name : "";
                        }

                        $value['category'] = $category_name;
                    }
                    if ($value['section_type'] == 1) {

                        $content =  explode(',', $value['content_ids']);

                        $content_name = [];

                        if ($value['product_content_type'] == 1) {
                            for ($i = 0; $i < count($content); $i++) {
                                $item = Items::where('id', $content[$i])->first();
                                $content_name[] = isset($item->id) ? $item->name : "";
                            }
                        }
                        $value['content'] = $content_name;
                    }
                }
            } else {
                $data = Section::where('is_home_screen', $request['is_home_screen'])->where('type_id', $request['top_type_id'])->orderBy('sortable', 'asc')->latest()->get();

                foreach ($data as $value) {
                    if ($value['content_type'] == 1) {
                        $category =  explode(',', $value['category_id']);

                        $category_name = [];

                        for ($i = 0; $i < count($category); $i++) {
                            $item = Category::where('id', $category[$i])->first();
                            $category_name[] = isset($item->id) ? $item->name : "";
                        }

                        $value['category'] = $category_name;
                    }
                    if ($value['section_type'] == 1) {
                        $content =  explode(',', $value['content_ids']);

                        $content_name = [];
                        if ($value['product_content_type'] == 1) {

                            for ($i = 0; $i < count($content); $i++) {
                                $item = Items::where('id', $content[$i])->first();
                                $content_name[] = isset($item->id) ? $item->name : "";
                            }
                        }
                        $value['content'] = $content_name;
                    }
                }
            }

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function SectionDataEdit(Request $request)
    {
        try {

            $data = Section::where('id', $request['id'])->first();

            if ($data['section_type'] == 1) {
                $data['content'] = $data['content_ids'] != null ? explode(',', $data['content_ids']) : [];

                $content_data = [];

                if ($data['product_content_type'] == 1) {
                    $query = Items::where('status', 1);
                }
                if (isset($query)) {
                    $content_data = $query->get();
                }

                $data['content_data'] = $content_data;
            }
            if ($data['background_type'] == 'image') {
                $data['bg_image'] = $this->common->getImage($this->folder, $data['background_value']);
            } else {
                $data['bg_color'] = $data['background_value'];
            }

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Sortable
    public function SectionSortable(Request $request)
    {
        try {
            $query = Section::select('id', 'title')
                ->where('is_home_screen', $request->is_home_screen);

            if ($request->is_home_screen == 2) {
                $query->where('type_id', $request->top_type_id);
            }

            $data = $query->orderBy('sortable', 'asc')->get();

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function SectionSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Section::where('id', $id_array[$i])->update(['sortable' => $i + 1]);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.success_sort_order')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function ChangeStatus($id)
    {
        try {
            $data = Section::where('id', $id)->first();
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
    public function get_content(Request $request)
    {
        try {

            $query = Items::where('is_approved', 1)->where('status', 1);

            if (isset($query)) {
                $data = $query->latest()->get();
            } else {
                $data = [];
            }

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_main_category(Request $request)
    {
        try {
            // dd($request);
            $query = Category::where('parent_category_id', 0)->where('status', 1);

            if (isset($query)) {
                $data = $query->orderBy('sort_order', 'asc')->get();
            } else {
                $data = [];
            }

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
