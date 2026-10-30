<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Common;
use App\Models\Hashtag;
use App\Models\Items;
use App\Models\ItemsAttributes;
use App\Models\ItemsFaq;
use App\Models\ItemsImages;
use App\Models\Review;
use App\Models\StockStatus;
use App\Models\Taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class ItemsController extends Controller
{
    private $folder = "product";
    private $folder_attributes = "attributes";
    private $folder_customer = "customer";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $seller = SellerData();

            $params['data'] = [];
            $params['brand'] = Brand::where('status', 1)->latest()->get();

            $input_search = $request['input_search'];
            $input_status = $request['input_status'];
            $input_brand = $request['input_brand_id'];


            $query = Items::where('seller_id', $seller['id']);

            if ($input_search != null && isset($input_search)) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('name', 'LIKE', "%{$input_search}%")->orWhere('sort_description', 'LIKE', "%{$input_search}%");
                });
            }
            if ($input_status != "all" && $input_status != null) {
                $query->where('product_type', $input_status);
            }

            if ($input_brand !== 'all' && $input_brand != null) {
                $query->where('brand_id', $input_brand);
            }

            $params['data'] = $query->orderBy('id', 'DESC')->paginate(15);

            $this->common->imageNameToUrl($params['data'], 'portrait_img', $this->folder);

            return view('seller.item.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {

            $params['tag'] = Hashtag::where('status', 1)->latest()->get();
            $params['taxes'] = Taxes::where('status', 1)->latest()->get();
            $params['stock_status'] = StockStatus::where('status', 1)->latest()->get();
            $params['brand'] = Brand::whereIn('type', ['all', 1])->where('status', 1)->latest()->get();
            $params['categories'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get();

            return view('seller.item.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        $seller = SellerData();

        $rules = [
            'name' => 'required|min:2',
            'category_id' => 'required',
            'brand_id' => 'required',
            'product_type' => 'required',
            'price' => 'required|numeric|min:0.01',
            'portrait_img' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'landscape_img' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'other_image.*' => 'mimes:jpg,jpeg,png|max:5120',
        ];
        // Add based on product_type
        if ($request->product_type == 1) {
            $rules['total_allowed_quantity'] = 'required|numeric|min:1';
            $rules['minimum_order_quantity'] = 'required|numeric|min:1';
            $rules['regular_delivery_charges'] = 'required|numeric|min:1';

            if ($request->has('tax_include_price') && $request->tax_include_price !== 'null') {
                $rules['tax_id'] = 'required';
            }

            if ($request->has('enable_stock') && $request->enable_stock !== 'null') {
                $rules['total_stock'] = 'required';
                $rules['stock_status_id'] = 'required';
            }

            if ($request->has('fast_delivery') && $request->fast_delivery !== 'null') {
                $rules['fast_delivery_charges'] = 'required|numeric|min:1';
            }
        }

        // Video or URL validation
        if ($request->video_type == 1) {
            $rules['video'] = 'required';
        } else {
            $rules['url'] = 'required|url';
        }

        // Create the validator
        $validator = Validator::make($request->all(), $rules);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->all()
            ]);
        }


        if (isset($request['tag_id']) && $request['tag_id'] != null) {
            $this->common->IncreaseUsedTags($request['tag_id']);
        }
        $tagId = is_array($request->input('tag_id')) ? implode(',', $request->input('tag_id')) : "";
        $taxId = is_array($request->input('tax_id')) ? implode(',', $request->input('tax_id')) : "";

        $item = new Items();
        $item['seller_id'] = $seller['id'];
        $item['category_id'] = $request['category_id'];
        $item['sub_category_id'] = $request['sub_category_id'];
        $item['brand_id'] = $request['brand_id'];
        $item['product_type'] = $request['product_type'];
        $item['name'] = $request['name'];
        $item['tag_id'] = $tagId;
        $item['price'] = $request['price'];
        $item['tax_include_price'] = $request->filled('tax_include_price') ? 1 : 0;
        $item['tax_id'] = $taxId;
        $item['enable_stock'] = $request->filled('enable_stock') ? 1 : 0;
        $item['total_stock'] = $request['total_stock'] ?? 0;
        $item['stock_status_id'] = $request['stock_status_id'] ?? 0;
        $item['total_allowed_quantity'] = $request['total_allowed_quantity'] ?? 0;
        $item['minimum_order_quantity'] = $request['minimum_order_quantity'] ?? 0;
        $item['minimum_free_order_quantity'] = $request['minimum_free_order_quantity'] ?? 0;
        $item['regular_delivery_charges'] = $request['regular_delivery_charges'] ?? 0;
        $item['fast_delivery'] = $request->filled('fast_delivery') ? 1 : 0;
        $item['fast_delivery_charges'] = $request['fast_delivery_charges'] ?? 0;
        $item['is_cod'] = $request->filled('is_cod') ? 1 : 0;
        $item['is_cancelable'] = $request->filled('is_cancelable') ? 1 : 0;
        $item['is_returnable'] = $request->filled('is_returnable') ? 1 : 0;
        $item['sort_description'] = $request['sort_description'] ?? "";
        $item['long_description'] = $request['long_description'] ?? "";
        $item['video_type'] = $request['video_type'];

        if ($request['video_type'] == 1) {
            $item['url'] = $request['video'];
        } else {
            $item['url'] = $request['url'] ?? "";
        }
        $item['is_approved'] = 0;
        $item['status'] = 0;
        if (isset($request['portrait_img'])) {
            $files = $request['portrait_img'];
            $item['portrait_img'] = $this->common->saveImage($files, $this->folder, 'product_');
        }
        if (isset($request['landscape_img'])) {
            $files1 = $request['landscape_img'];
            $item['landscape_img'] = $this->common->saveImage($files1, $this->folder, 'product_');
        }

        if ($item->save()) {

            if (isset($request['array'])) {
                foreach ($request['array'] as  $value) {
                    $this->common->Item_Amenity_Value_Add($value, $item['id']);
                }
            }
            // FAQs
            $arr_question = $request['question'];
            $arr_answer = $request['answer'];

            for ($i = 0; $i < count($arr_question); $i++) {

                if (!empty($arr_question[$i]) && !empty($arr_answer[$i])) {

                    $insert = new ItemsFaq();
                    $insert['item_id'] = $item['id'];
                    $insert['question'] = $arr_question[$i];
                    $insert['answer'] = $arr_answer[$i];
                    $insert['status'] = 1;
                    $insert->save();
                }
            }

            // Other Images
            if ($request->hasFile('other_image')) {

                $uploadedFiles = $request->file('other_image');

                foreach ($uploadedFiles as $index => $file) {

                    if ($file != null) {

                        $other_img = new ItemsImages();
                        $other_img['item_id'] = $item['id'];
                        $other_img['image'] = $this->common->saveImage($file, $this->folder, 'other_');
                        $other_img['status'] = 1;
                        $other_img->save();
                    }
                }
            }

            $Noti = $this->common->BasicNotiConfiguration(8);
            if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                $array = [
                    'product_name' => $item->name,
                ];
                $this->common->Send_Mail(10, $seller['email'], $array);
            }
            if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                $title = "Product Submission Received – Awaiting Approval!";
                $message = "You have successfully added" . " " . $item['name'] . " " . " to your store. Please wait while our team reviews it. Once approved, your product will be live and available for customers!";
                $this->common->SaveNotification('user', 4, $title, $message, $seller['id'], $item['id'], 0, 0, "",  "", "", $seller['device_token'], $seller['device_type']);
            }
            return response()->json(['status' => 200, 'success' => __('label.controller.product_save')]);
        } else {
            return response()->json(['status' => 400, 'errors' => __('label.controller.product_not_save')]);
        }
    }
    public function edit($id)
    {
        try {

            $params['tag'] = Hashtag::where('status', 1)->latest()->get();
            $params['taxes'] = Taxes::where('status', 1)->latest()->get();
            $params['stock_status'] = StockStatus::where('status', 1)->latest()->get();
            $params['brand'] = Brand::whereIn('type', ['all', 1])->where('status', 1)->latest()->get();
            $params['categories'] = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc')->with('sub_category')->latest()->get();

            $params['result'] = Items::where('id', $id)->first();
            $this->common->imageNameToUrl(array($params['result']), 'portrait_img', $this->folder);
            $this->common->imageNameToUrl(array($params['result']), 'landscape_img', $this->folder);

            //Other Images
            $params['other_img'] = ItemsImages::where('item_id', $id)->get();
            $this->common->imageNameToUrl($params['other_img'], 'image', $this->folder);

            //Faq
            $params['faq'] = ItemsFaq::where('item_id', $id)->latest()->get()->toArray();

            //Attributes
            $get_amenity = Attributes::where('status', 1)->get();
            $params['product_amenity'] = collect();
            if ($get_amenity->isNotEmpty()) {
                $array = [];

                foreach ($get_amenity as $item) {
                    $array_category_id = explode(",", $item->category_id);
                    // Check if sub_category_id is set and not 0
                    $checkId = $params['result']['sub_category_id'] != 0
                        ? $params['result']['sub_category_id']
                        : $params['result']['category_id'];

                    if (in_array($checkId, $array_category_id)) {
                        $array[] = $item->id;
                    }
                }

                $params['product_amenity'] = Attributes::whereIn('id', $array)->latest()->get();

                if ($params['product_amenity']->isNotEmpty()) {
                    foreach ($params['product_amenity'] as $i => $amenity) {
                        $amenity_id = $amenity->id;

                        // For text or numeric fields (field_type == 1 or field_type == 2)
                        if ($amenity->field_type == 1 || $amenity->field_type == 2) {
                            $get_value = ItemsAttributes::where('item_id', $id)
                                ->where('attributes_id', $amenity_id)
                                ->first();

                            if ($get_value) {
                                $params['product_amenity'][$i]['product_value'] = $get_value->option;
                            }
                        }

                        // For image fields (field_type == 6)
                        else if ($amenity->field_type == 6) {
                            $get_value = ItemsAttributes::where('item_id', $id)
                                ->where('attributes_id', $amenity_id)
                                ->first();

                            if ($get_value) {
                                $params['product_amenity'][$i]['product_value'] = $this->common->getImage($this->folder, $get_value->option);
                            } else {
                                $params['product_amenity'][$i]['product_value'] = asset('assets/imgs/upload_img.png');
                            }
                        }

                        // For select, checkbox, or other fields (field_type != 1, 2, 6)
                        else {
                            $get_value = AttributesOption::where('attributes_id', $amenity_id)->get();

                            if ($get_value->isNotEmpty()) {
                                $params['product_amenity'][$i]['amenity_value'] = $get_value;
                            }

                            $get_product_value = ItemsAttributes::where('item_id', $id)
                                ->where('attributes_id', $amenity_id)
                                ->first();

                            if ($get_product_value) {
                                if ($amenity->field_type == 5) {
                                    // For checkboxes (field_type == 5)
                                    $product_value = explode(",", $get_product_value->option);
                                    $params['product_amenity'][$i]['product_value'] = $product_value;
                                } else {
                                    // For other field types
                                    $params['product_amenity'][$i]['product_value'] = $get_product_value->option;
                                }
                            }
                        }
                    }
                }
            }


            if ($params['result'] != null) {
                return view('seller.item.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.page_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        $rules = [
            'name' => 'required|min:2',
            'category_id' => 'required',
            'brand_id' => 'required',
            'product_type' => 'required',
            'price' => 'required|numeric|min:0.01',
            'portrait_img' => 'image|mimes:jpeg,png,jpg|max:5120',
            'landscape_img' => 'image|mimes:jpeg,png,jpg|max:5120',
            'other_image.*' => 'mimes:jpg,jpeg,png|max:5120',
        ];
        // Add based on product_type
        if ($request->product_type == 1) {
            $rules['total_allowed_quantity'] = 'required|numeric|min:1';
            $rules['minimum_order_quantity'] = 'required|numeric|min:1';
            $rules['regular_delivery_charges'] = 'required|numeric|min:1';

            if ($request->has('tax_include_price') && $request->tax_include_price !== 'null') {
                $rules['tax_id'] = 'required';
            }

            if ($request->has('enable_stock') && $request->enable_stock !== 'null') {
                $rules['total_stock'] = 'required';
                $rules['stock_status_id'] = 'required';
            }

            if ($request->has('fast_delivery') && $request->fast_delivery !== 'null') {
                $rules['fast_delivery_charges'] = 'required|numeric|min:1';
            }
        }
        // Video or URL validation
        if ($request->video_type == 2) {
            $rules['url'] = 'required|url';
        }

        // Create the validator
        $validator = Validator::make($request->all(), $rules);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->all()
            ]);
        }

        $item = Items::where('id', $request['id'])->first();
        if (isset($item['id'])) {

            $tagId = is_array($request->input('tag_id')) ? implode(',', $request->input('tag_id')) : "";
            $taxId = is_array($request->input('tax_id')) ? implode(',', $request->input('tax_id')) : "";

            $item['category_id'] = $request['category_id'];
            $item['sub_category_id'] = $request['sub_category_id'];
            $item['brand_id'] = $request['brand_id'];
            $item['product_type'] = $request['product_type'];
            $item['name'] = $request['name'];
            $item['tag_id'] = $tagId;
            $item['price'] = $request['price'];
            $item['tax_include_price'] = $request->filled('tax_include_price') ? 1 : 0;
            $item['tax_id'] = $taxId;
            $item['enable_stock'] = $request->filled('enable_stock') ? 1 : 0;
            $item['total_stock'] = $request['total_stock'] ?? 0;
            $item['stock_status_id'] = $request['stock_status_id'] ?? 0;
            $item['total_allowed_quantity'] = $request['total_allowed_quantity'] ?? 0;
            $item['minimum_order_quantity'] = $request['minimum_order_quantity'] ?? 0;
            $item['minimum_free_order_quantity'] = $request['minimum_free_order_quantity'] ?? 0;
            $item['regular_delivery_charges'] = $request['regular_delivery_charges'] ?? 0;
            $item['fast_delivery'] = $request->filled('fast_delivery') ? 1 : 0;
            $item['fast_delivery_charges'] = $request['fast_delivery_charges'] ?? 0;
            $item['is_cod'] = $request->filled('is_cod') ? 1 : 0;
            $item['is_cancelable'] = $request->filled('is_cancelable') ? 1 : 0;
            $item['is_returnable'] = $request->filled('is_returnable') ? 1 : 0;
            $item['sort_description'] = $request['sort_description'] ?? "";
            $item['long_description'] = $request['long_description'] ?? "";
            $item['video_type'] = $request['video_type'];
            if ($request['video_type'] == 1) {
                if ($request['old_video_url'] != null) {
                    if (isset($request->video)) {
                        $item['url'] = $request->video;
                        $this->common->deleteImageToFolder($this->folder, basename($request['old_video_url']));
                    } else {
                        $item['url'] = basename($request['old_video_url']);
                    }
                }
            } else {
                $item['url'] = $request['url'];
            }

            if (isset($request['portrait_img'])) {
                $files = $request['portrait_img'];
                $item['portrait_img'] = $this->common->saveImage($files, $this->folder, 'product_');
                $this->common->deleteImageToFolder($this->folder, basename($request->old_portrait_img));
            }
            if (isset($request['landscape_img'])) {
                $files1 = $request['landscape_img'];
                $item['landscape_img'] = $this->common->saveImage($files1, $this->folder, 'product_');
                $this->common->deleteImageToFolder($this->folder, basename($request->old_landscape_img));
            }

            if ($item->save()) {

                $this->common->Update_Item_Amenity_value($request['array'], $item->id);

                // FAQs
                $old_faq_id = $request->input('old_faq_id', []);
                $arr_question = $request->input('question', []);
                $arr_answer = $request->input('answer', []);

                $existingFaqs = ItemsFaq::where('item_id', $id)->get();
                foreach ($existingFaqs as $faq) {

                    if (!in_array($faq['id'], $old_faq_id)) {
                        $faq->delete();
                    }
                }
                for ($i = 0; $i < count($old_faq_id); $i++) {

                    if (isset($arr_question[$i]) && $arr_question[$i] != null && isset($arr_answer[$i]) && $arr_answer[$i] != null) {

                        if ($old_faq_id[$i] != null) {

                            $update = ItemsFaq::where('id', $old_faq_id[$i])->first();
                            if (isset($update) && $update != null) {

                                $update['question'] = $arr_question[$i];
                                $update['answer'] = $arr_answer[$i];
                                $update->save();
                            }
                        } else {

                            $insert = new ItemsFaq();
                            $insert['item_id'] = $id;
                            $insert['question'] = $arr_question[$i];
                            $insert['answer'] = $arr_answer[$i];
                            $insert['status'] = 1;
                            $insert->save();
                        }
                    } else {

                        if ($old_faq_id[$i] != null) {
                            ItemsFaq::where('id', $old_faq_id[$i])->delete();
                        }
                    }
                }


                // Other Images
                $old_other_image_id = $request->input('old_other_image_id', []);
                $old_other_image = $request->input('old_other_image', []);
                $other_image = $request->file('other_image', []);

                $existingImages = ItemsImages::where('item_id', $id)->get();
                foreach ($existingImages as $image) {
                    if (!in_array($image['image'], $old_other_image)) {

                        $this->common->deleteImageToFolder($this->folder, $image['image']);
                        $image->delete();
                    }
                }
                for ($i = 0; $i < count($old_other_image_id); $i++) {

                    if ($old_other_image_id[$i] != null) {

                        if (isset($other_image[$i]) && $other_image[$i] != null) {

                            $update = ItemsImages::where('id', $old_other_image_id[$i])->first();
                            if (isset($update) && $update != null) {

                                $update['image'] = $this->common->saveImage($other_image[$i], $this->folder, 'other_');

                                $this->common->deleteImageToFolder($this->folder, $old_other_image[$i]);
                                $update->save();
                            }
                        }
                    } else {
                        if (isset($other_image[$i]) && $other_image[$i] != null) {

                            $insert = new ItemsImages();
                            $insert['item_id'] = $id;
                            $insert['image'] = $this->common->saveImage($other_image[$i], $this->folder, 'other_');
                            $insert['status'] = 1;
                            $insert->save();
                        }
                    }
                }
                return response()->json(['status' => 200, 'success' => __('label.controller.product_update')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.controler.product_not_update')]);
            }
        }
    }
    public function show($id)
    {
        try {
            $data = Items::where('id', $id)->first();

            $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
            $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
            $this->common->deleteImageToFolder($this->folder, $data['url']);
            $data->delete();

            Items::DeleteAllData($id);

            return redirect()->route('seller.item.index')->with('success', __('label.controller.product_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function ChangeStatus($id)
    {
        try {
            $data = Items::where('id', $id)->first();
            if (isset($data)) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(array('status' => 200, 'success' => __('label.status_changed'), 'id' => $data->id, 'Status' => $data->status));
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function GetAttributes(Request $request)
    {
        try {
            $category_id = $request['cat_id'];
            $get_custom = Attributes::get();
            if (!empty(sizeof($get_custom))) {
                $array = [];

                foreach ($get_custom as $item) {
                    $array_category_id = explode(",", $item->category_id);
                    if (in_array($category_id, $array_category_id)) {
                        $array[] = $item->id;
                    }
                }
                $data = Attributes::whereIn('id', $array)->where('status', 1)->latest()->get();
                if (!empty(sizeof($data))) {

                    foreach ($data as $value) {

                        $value['attributes_option'] = [];
                        if ($value['field_type'] == 3 || $value['field_type'] == 4 || $value['field_type'] == 5) {
                            $attributes_option = AttributesOption::where('attributes_id', $value['id'])->get();
                            if (!empty(sizeof($attributes_option))) {
                                $value['attributes_option'] = $attributes_option;
                            }
                        }
                    }
                    return response()->json(array('status' => 200, 'success' => "Data Get Succesfully", 'result' => $data));
                } else {
                    return response()->json(array('status' => 400, 'errors' => "Data Not Found", 'result' => array()));
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function Details($item_id)
    {
        try {
            $params['data'] = Items::where('id', $item_id)->with('category', 'brand')->first();

            $params['TagName'] = $this->common->DetailsGetTagsName($params['data']['tag_id']);
            $params['Taxname'] = $this->common->DetailsGetTaxsName($params['data']['tax_id']);
            $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);

            $params['other_img'] = ItemsImages::where('item_id', $item_id)->get();
            $this->common->imageNameToUrl($params['other_img'], 'image', $this->folder);

            $params['p_attributes'] = ItemsAttributes::where('item_id', $item_id)->with('attributes')->get();

            foreach ($params['p_attributes'] as $key => $value) {
                $this->common->imageNameToUrl(array($value->attributes), 'icon', $this->folder_attributes);
                if ($value['field_type'] == 6) {
                    $this->common->imageNameToUrl(array($value), 'option', $this->folder);
                }
            }

            //Review
            $params['review'] = Review::where('content_id', $item_id)->where('type', 1)->with('customer')->latest()->get();
            foreach ($params['review'] as $value) {
                $value->customer->image = $this->common->getImage($this->folder_customer, $value->customer->image);
                $value['review_date'] =  $this->common->GetDateTime($value['updated_at']);
            }
            //Faq
            $params['faq'] = ItemsFaq::where('item_id', $item_id)->latest()->get();

            return view('seller.item.detail', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
