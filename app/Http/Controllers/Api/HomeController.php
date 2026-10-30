<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AddToCart;
use App\Models\Article;
use App\Models\Attributes;
use App\Models\AttributesOption;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Common;
use App\Models\Customer;
use App\Models\DeliveryInstructor;
use App\Models\General_Setting;
use App\Models\Items;
use App\Models\ItemsAttributes;
use App\Models\ItemsFaq;
use App\Models\ItemsImages;
use App\Models\Notification;
use App\Models\NotificationTracking;
use App\Models\Offers;
use App\Models\Onboarding_Screen;
use App\Models\OrderCancel;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\Page;
use App\Models\Payment_Option;
use App\Models\Promocode;
use App\Models\Reason;
use App\Models\Review;
use App\Models\Section;
use App\Models\Social_Link;
use App\Models\TrackIntrest;
use App\Models\Transaction;
use App\Models\Type;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class HomeController extends Controller
{
    private $folder_setting = "setting";
    private $folder_offers = "offers";
    private $folder_promocode = "promocode";
    private $folder_item = "product";
    private $folder_category = "category";
    private $folder_article = "article";
    private $folder_attributes = "attributes";
    private $folder_customer = "customer";
    private $folder_review = "review";
    private $folder_type = "type";
    private $folder_section = "section";
    private $folder_delivery_instructor = "delivery_instructor";
    public $common;
    public $page_limit;

    public function __construct()
    {
        try {

            $this->common = new Common();
            $this->page_limit = env('PAGE_LIMIT') ?: 10;
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong. Please try again.',
                'errors' => 'Something went wrong. Please try again.',
                'result' => [],
            ]);
        }
    }
    public function GeneralSetting()
    {
        try {

            $list = General_Setting::get();
            foreach ($list as $value) {

                if ($value['key'] == 'app_logo') {
                    $value['value'] = $this->common->getImage($this->folder_setting, $value['value']);
                }

                if ($value['key'] == 'company_logo') {
                    $value['value'] = $this->common->getImage($this->folder_setting, $value['value']);
                }
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $list);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetPaymentOption()
    {
        try {

            $return['status'] = 200;
            $return['message'] = __('api_msg.data_retrieved');
            $return['result'] = [];

            $data = Payment_Option::get();
            foreach ($data as $value) {
                $return['result'][$value['name']] = $value;
            }

            return $return;
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetOnboardingScreen()
    {
        try {
            $data = Onboarding_Screen::get();
            if (sizeof($data) > 0) {
                $this->common->imageNameToUrl($data, 'image', $this->folder_setting);
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetSocialLink()
    {
        try {
            $data = Social_Link::get();
            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_setting);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetPages()
    {
        try {
            $return['status'] = 200;
            $return['message'] = __('api_msg.data_retrieved');
            $return['result'] = [];

            $data = Page::get();
            for ($i = 0; $i < count($data); $i++) {
                $return['result'][$i]['title'] = $data[$i]['title'];
                $return['result'][$i]['url'] = env('APP_URL') . '/public/pages/' . $data[$i]['title'];
                $return['result'][$i]['icon'] = $this->common->getImage($this->folder_setting, $data[$i]['icon']);
            }
            return $return;
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetType()
    {
        try {
            $data = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();

            if (sizeof($data) > 0) {

                $this->common->imageNameToUrl($data, 'icon', $this->folder_type);
                $this->common->imageNameToUrl($data, 'banner_image', $this->folder_type);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetBanner(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'is_home_screen' => 'required|numeric',
                'type_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $is_home_screen = $request->is_home_screen;
            $type_id = $request->type_id;

            if ($is_home_screen == 1) {
                $data = Banner::where('is_home_screen', $is_home_screen)->where('status', 1)->with('offers', 'item')->orderBy('id', 'desc')->latest()->get();
            } else if ($is_home_screen == 2) {
                $data = Banner::where('is_home_screen', $is_home_screen)->where('type_id', $type_id)->where('status', 1)->with('offers', 'item')->orderBy('id', 'desc')->latest()->get();
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            if (count($data) > 0) {
                $final_data = [];
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['content_type'] == 1) {
                        $data[$i]['offers']['image'] = $this->common->getImage($this->folder_offers, $data[$i]['offers']->image);
                        $data[$i]['offers']['banner_image'] = $this->common->getImage($this->folder_offers, $data[$i]['offers']->banner_image);
                        $final_data[] = $data[$i]['offers'];
                    } elseif ($data[$i]['content_type'] == 2) {
                        $data[$i]['item']['portrait_img'] = $this->common->getImage($this->folder_item, $data[$i]['item']->portrait_img);
                        $data[$i]['item']['landscape_img'] = $this->common->getImage($this->folder_item, $data[$i]['item']->landscape_img);
                        if ($data[$i]['item']['video_type'] == 1) {
                            $data[$i]['item']['url'] = $this->common->getImage($this->folder_item, $data[$i]['item']->url);
                        }
                        $final_data[] = $data[$i]['item'];
                    }
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $final_data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetSection(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'is_home_screen' => 'required|numeric',
                    'type_id' => 'required|numeric',        
                    'customer_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $is_home_screen = $request['is_home_screen'];
            $type_id = $request['type_id'];
            $customer_id = isset($request->customer_id) ? $request->customer_id : 0;


            if ($is_home_screen == 1) {
                $data = Section::where('is_home_screen', $is_home_screen)->where('status', 1)->orderBy('sortable', 'asc')->latest();
            } else if ($is_home_screen == 2) {
                $data = Section::where('is_home_screen', $is_home_screen)->where('type_id', $type_id)->where('status', 1)->orderBy('sortable', 'asc')->latest();
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;
            $data->take($total_page)->offset($offset);

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['background_type'] == 'image') {
                        $this->common->imageNameToUrl(array($data[$i]), 'background_value', $this->folder_section);
                    }
                    $data[$i]['data'] = [];

                    // $query = $this->common->SectionQueryData($customer_id, $data[$i]['section_type'], $data[$i]['content_type'], $data[$i]['screen_layout'], $data[$i]['content_ids'], $data[$i]['category_id'], $data[$i]['sub_category_id'], $data[$i]['brand_id'], $data[$i]['seller_id'], $data[$i]['product_content_type'], $data[$i]['selling_type'], $data[$i]['include_low_price'], $data[$i]['include_high_price'], $data[$i]['order_by_upload'], $data[$i]['order_by_like'], $data[$i]['no_of_content']);
                    // $data[$i]['data'] = $query;
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetSectionDetails(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'section_id' => 'required|numeric',
                    'customer_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $section_id = $request['section_id'];
            $customer_id = isset($request->customer_id) ? $request->customer_id : 0;
            $page_no = $request->page_no ?? 1;
            $section = Section::where('id', $section_id)->where('status', 1)->first();

            if ($section != null && isset($section)) {
                $final_array = $this->common->SectionDetailsQuery($customer_id, $section['section_type'], $section['content_type'], $section['screen_layout'], $section['content_ids'], $section['category_id'], $section['sub_category_id'], $section['brand_id'], $section['seller_id'], $section['product_content_type'], $section['selling_type'], $section['include_low_price'], $section['include_high_price'], $section['order_by_upload'], $section['order_by_like'], $section['no_of_content']);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $currentItems = array_slice($final_array, $this->page_limit * ($page_no - 1), $this->page_limit);
            $paginator = new LengthAwarePaginator($currentItems, count($final_array), $this->page_limit, $page_no);

            $more_page = $this->common->more_page($page_no, $paginator->lastPage());
            $pagination = $this->common->pagination_array($paginator->total(), $paginator->lastPage(), $page_no, $more_page);
            $data = $paginator->items();

            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetCategoryWithSubCategory(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'category_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $category_id = isset($request->category_id) ? $request->category_id : 0;

            if ($category_id == 0) {
                $data = Category::where('parent_category_id', 0)->where('status', 1)->orderBy('sort_order', 'asc');
            } else {
                $data = Category::where('parent_category_id', $category_id)->where('status', 1)->orderBy('sort_order', 'asc');
            }
            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $this->common->imageNameToUrl($data, 'image', $this->folder_category);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetItemsByCategory(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'category_id' => 'required|numeric',
                    'sub_category_id' => 'numeric',
                ],
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $category_id = $request['category_id'];
            $sub_category_id = isset($request->sub_category_id) ? $request->sub_category_id : 0;
            $customer_id = isset($request['customer_id']) ? $request['customer_id'] : 0;

            if ($sub_category_id == 0) {
                $data = Items::where('category_id', $category_id)->where('is_approved', 1)->where('status', 1)->latest();
            } else {
                $data = Items::where('sub_category_id', $sub_category_id)->where('is_approved', 1)->where('status', 1)->latest();
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $data = $this->common->CommonItemsDetails($data, $customer_id);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function SearchItems(Request $request)
    {
        try {

            $query = $request->input('query', '');
            $category_id = isset($request->category_id) ? $request->category_id : 0;
            $sub_category_id = isset($request->sub_category_id) ? $request->sub_category_id : 0;
            $brand_id = isset($request->brand_id) ? $request->brand_id : 0;
            $product_type = isset($request->product_type) ? $request->product_type : 0;
            $min_price = isset($request->min_price) ? $request->min_price : 0;
            $max_price = isset($request->max_price) ? $request->max_price : 0;
            $customer_id = isset($request->customer_id) ? $request->customer_id : 0;

            $page_size = 0;
            $current_page = 0;
            $more_page = false;

            $data = Items::where('is_approved', 1)->where('status', 1);

            if ($query != '') {
                $data->where('name', 'like', '%' . $query . '%')
                    ->orWhere('sort_description', 'like', '%' . $query . '%');
            }
            if ($category_id != 0) {
                $data->where('category_id', $category_id);
            }
            if ($sub_category_id != 0) {
                $data->where('sub_category_id', $sub_category_id);
            }
            if ($brand_id != 0) {
                $data->where('brand_id', $brand_id);
            }
            if ($product_type != 0) {
                $data->where('product_type', $product_type);
            }
            if ($min_price != 0 && $max_price != 0) {
                $data->whereBetween('price', [$min_price, $max_price]);
            } elseif ($min_price != 0) {
                $data->where('price', '>=', $min_price);
            } elseif ($max_price != 0) {
                $data->where('price', '<=', $max_price);
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request->page_no ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->get();

            if (count($data) > 0) {
                $data = $this->common->CommonItemsDetails($data, $customer_id);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddRemoveWishlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                    'item_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request['customer_id'];
            $item_id = $request['item_id'];

            $DataExists = Wishlist::where('customer_id', $customer_id)->where('item_id', $item_id)->first();
            if ($DataExists == null) {
                $insert = new Wishlist();
                $insert->customer_id = $customer_id;
                $insert->item_id = $item_id;
                $insert->status = 1;

                if ($insert->save()) {
                    return $this->common->API_Response(200, __('api_msg.add_to_bookmark'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                $DataExists->delete();
                return $this->common->API_Response(400, __('api_msg.removed_from_bookmark'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetWishlist(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request['customer_id'];

            $wishlistItemIds = Wishlist::where('customer_id', $customer_id)->where('status', 1)->pluck('item_id');

            $data = Items::whereIn('id', $wishlistItemIds);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $data = $this->common->CommonItemsDetails($data, $customer_id);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ItemsDetails(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                    'customer_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $item_id = $request['item_id'];
            $customer_id = isset($request['customer_id']) ? $request['customer_id'] : 0;

            $data = Items::where('id', $item_id)->first();
            if (isset($data) && $data != null) {

                $data = $this->common->GetItemFullDetails($data, $customer_id);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetTopRatingItems(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [

                    'customer_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = isset($request['customer_id']) ? $request['customer_id'] : 0;
            $topRatedItemIds = Review::select('content_id', DB::raw('AVG(rating) as avg_rating'))->where('type', 1)->where('status', 1)->groupBy('content_id')->orderByDesc('avg_rating')->pluck('content_id')->toArray();
            $topRatedItemIds = array_values(array_unique(array_filter(array_map('intval', $topRatedItemIds))));

            $total_page = $this->page_limit > 0 ? $this->page_limit : 10;
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            if (empty($topRatedItemIds)) {
                $pagination = $this->common->pagination_array(0, 0, $current_page, false);
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), [], $pagination);
            }

            $data = Items::whereIn('id', $topRatedItemIds)->where('seller_id', '!=', $customer_id)->orderByRaw('FIELD(id, ' . implode(',', $topRatedItemIds) . ')');

            $total_rows = $data->count();
            $page_size = $total_page > 0 ? ceil($total_rows / $total_page) : 0;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data = $data->skip($offset)->take($total_page)->get();

            if (count($data) > 0) {
                $data = $this->common->CommonItemsDetails($data, $customer_id);
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetSimilarItems(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                    'customer_id' => 'numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $item_id = $request['item_id'];
            $customer_id = isset($request['customer_id']) ? $request['customer_id'] : 0;

            $items = Items::where('id', $item_id)->first();

            $category_id = 0;
            $sub_category_id = 0;
            if (isset($items) && $items != null) {
                $category_id = $items['category_id'];
                $sub_category_id = $items['sub_category_id'];
            }
            $data = Items::where('id', '!=', $item_id)->where('seller_id', '!=', $customer_id)->where('status', 1)->where('is_approved', 1);

            if ($sub_category_id != 0) {
                $data->where('sub_category_id', $sub_category_id);
            } else {
                $data->where('category_id', $category_id);
            }

            $data = $data;

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $data = $this->common->CommonItemsDetails($data, $customer_id);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetOffers(Request $request)
    {
        try {
            $this->common->ActiveExpriedOffers();

            $data = Offers::with('item')->where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                foreach ($data as $value) {
                    $value['item_title'] = "";
                    $value['item_price'] = 0;
                    $value['item_image'] = "";
                    if (isset($value->item) && $value->item != null) {
                        $value['item_title'] = $value->item->name;
                        $value['item_price'] = $value->item->price;
                        $value['item_image'] = $this->common->getImage($this->folder_item, $value->item->portrait_img);
                    }
                    unset($value->item);
                }

                $this->common->imageNameToUrl($data, 'image', $this->folder_offers);
                $this->common->imageNameToUrl($data, 'banner_image', $this->folder_offers);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetPromocodes(Request $request)
    {
        try {
            $this->common->ActiveExpriedPromocodes();

            $data = Promocode::where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $this->common->imageNameToUrl($data, 'image', $this->folder_promocode);
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetOrdersStatus()
    {
        try {
            $return['result'][0]['name'] = __('label.all');
            $return['result'][0]['value'] = 0;

            $return['result'][1]['name'] = __('label.pending');
            $return['result'][1]['value'] = 1;

            $return['result'][2]['name'] = __('label.confirmed');
            $return['result'][2]['value'] = 2;

            $return['result'][3]['name'] = __('label.picking');
            $return['result'][3]['value'] = 3;

            $return['result'][4]['name'] = __('label.packed');
            $return['result'][4]['value'] = 4;

            $return['result'][5]['name'] = __('label.out_for_delivery');
            $return['result'][5]['value'] = 5;

            $return['result'][6]['name'] = __('label.delivered');
            $return['result'][6]['value'] = 6;

            $return['result'][7]['name'] = __('label.cancelled');
            $return['result'][7]['value'] = 7;

            $return['result'][8]['name'] = __('label.rejected');
            $return['result'][8]['value'] = 8;

            return $return;
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetCustomerOrders(Request $request) //0-all ,1-Pending ,2-Confirmed , 3-Picking , 4-Packed , 5-Out-for-delivery , 6-Delivered, 7-Cancelled, 8-Rejected
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                    'order_status' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];
            $order_status = $request['order_status'];

            if ($order_status == 0) {
                $data = Orders::where('customer_id', $customer_id)->with('customer', 'customer_address', 'seller');
            } else {
                $data = Orders::where('order_status', $order_status)->where('customer_id', $customer_id)->with('customer', 'customer_address', 'seller');
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $this->common->CommonOrderDetails($data);
            }

            return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function SearchOrders(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                    'query' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $data = Orders::where('customer_id', $request->customer_id)->with('customer', 'customer_address', 'seller');

            $search = $request->input('query');
            if (!empty($search)) {
                $data->where(function ($q) use ($search) {
                    $q->where('group_order_id', 'like', "%$search%")
                        ->orWhere('delivery_otp', 'like', "%$search%")
                        ->orWhere('total_payable_amount', 'like', "%$search%");
                });
            }

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                $this->common->CommonOrderDetails($data);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddToCart(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [

                    'item_id' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                    'quantity' => 'numeric',
                    'price' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $item = Items::find($request->item_id);
            if (!$item) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }

            $quantity = isset($request->quantity) ?: 1;

            $totalAmount = $request->price * $quantity;

            $insert = new AddToCart();
            $insert->customer_id = $request->customer_id;
            $insert->item_id = $request->item_id;
            $insert->quantity = $quantity;
            $insert->variation = $request->variation ?? '';
            $insert->item_price = $request->price;
            $insert->total_price = $totalAmount;
            $insert->status = 1;

            if ($insert->save()) {
                return $this->common->API_Response(200, __('api_msg.items_add_to_cart'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function UpdateCartsQuantity(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                    'quantity' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $item_id = $request['item_id'];
            $customer_id = $request['customer_id'];
            $quantity = $request['quantity'];

            $cart = AddToCart::where('item_id', $item_id)->where('customer_id', $customer_id)->first();
            if (isset($cart) && $cart != null) {
                $item = Items::find($request->item_id);
                $price = $item->price;

                $cart->quantity = $quantity;
                $cart->total_price = $price * $quantity;
                $cart->save();
                if ($cart->save()) {
                    return $this->common->API_Response(200, __('api_msg.quantity_updates'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.quantity_not_updates'));
                }
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function RemoveCart(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $item_id = $request['item_id'];
            $customer_id = $request['customer_id'];

            $items = AddToCart::where('item_id', $item_id)->where('customer_id', $customer_id)->first();
            if (isset($items) && $items != null) {
                $items->delete();
                return $this->common->API_Response(200, __('api_msg.remove_cart_item'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_deleted'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetCartsItems(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];

            $data = AddToCart::where('customer_id', $customer_id)->where('status', 1)->with('item');

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {
                foreach ($data as $value) {

                    $item = $value['item'];

                    $value['item_title'] = $item['name'] ?? "";
                    $value['item_image'] = !empty($item['portrait_img']) ? $this->common->getImage($this->folder_item, $item['portrait_img']) : "";
                    $value['item_unit'] = $item['unit'] ?? "";
                    $value['item_per_unit'] = $item['per_unit'] ?? 0;
                    $value['item_total_stock'] = $item['total_stock'] ?? 0;
                    $value['item_enable_stock'] = $item['enable_stock'] ?? 1;
                    $value['is_bookmark'] = $this->common->is_bookmark($customer_id, $item['id']);

                    unset($value->item);
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ApplyPromocode(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'promo_code_id' => 'required',
                    'total_price' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $promocode_id = $request['promo_code_id'];
            $total_price = $request['total_price'];

            $promo_code = Promocode::where('id', $promocode_id)->first();

            if (isset($promo_code) && $promo_code != null) {

                if ($promo_code['type'] == 1) {
                    $discount_price = $promo_code['discount_amount'];
                } else {
                    $discount_price = round(($total_price * $promo_code['discount_amount']) / 100);
                }
                $data['price'] = $total_price - $discount_price;
                $data['discount_amount'] = $discount_price;

                return $this->common->API_Response(200, __('api_msg.promo_code_applied'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.promo_code_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function BuyOrders(Request $request)
    {
        try {

            $request->validate([
                'customer_id' => 'required',
                'item_details' => 'required|json',
                'customer_address_id' => 'required|numeric',
                'payment_method' => 'required|numeric',
                'payment_status' => 'required|numeric',
                'total_payable_amount' => 'required',
            ]);

            $items = json_decode($request->item_details, true);

            if (empty($items)) {
                return response()->json(['error' => 'Cart empty']);
            }

            /* ===== SETTINGS ===== */
            $settings = Setting_Data();
            $deliveryCommissionType  = $settings['delivery_boy_commission_type'];
            $deliveryCommissionValue = $settings['delivery_boy_commission_value'];
            $sellerCommissionType  = $settings['seller_commission_type'];
            $sellerCommissionValue = $settings['seller_commission_value'];

            /* ===== EXTRA DATA ===== */
            $deliveryCharges = $request->delivery_charges ?? 0;
            $handlingCharges = $request->handling_charges ?? 0;
            $donationAmount  = $request->donation_amount ?? 0;
            $discountAmount  = $request->discount_amount ?? 0;
            $promoCodeId     = $request->promo_code_id ?? 0;
            $orderNotes      = $request->order_notes ?? "";
            $deliveryInstructor = $request->delivery_instructor_id ?? "";

            /* ===== GROUP SELLER ===== */
            $sellerWise = [];
            $cartSubtotal = 0;

            foreach ($items as $val) {
                $itemData = Items::find($val['item_id']);
                if (!$itemData) continue;

                $sellerWise[$itemData->seller_id][] = $val;
                $cartSubtotal += $val['total_price'];
            }

            $customerOrderIds = [];
            $customer = Customer::find($request->customer_id);

            /* ===== SPLIT TRACKERS ===== */
            $allocatedDiscount = 0;
            $allocatedDelivery = 0;
            $allocatedHandling = 0;
            $allocatedDonation = 0;

            $sellerCount = count($sellerWise);
            $currentIndex = 1;

            /* ===== CREATE SELLER ORDERS ===== */
            foreach ($sellerWise as $sellerId => $sellerItems) {

                $seller = Customer::find($sellerId);
                if (!$seller) continue;

                /* 🔥 UNIQUE group_order_id per seller */
                $groupSequenceId = Orders::max('id') + 1;
                $groupOrderId = 'GRP' . date('Y') . 'ODR' . str_pad($groupSequenceId, 3, '0', STR_PAD_LEFT);

                $customerOrderIds[] = $groupOrderId;

                $sellerSubtotal = array_sum(array_column($sellerItems, 'total_price'));

                /* ==== SPLIT CALC ==== */
                if ($currentIndex == $sellerCount) {

                    $sellerDiscount = $discountAmount - $allocatedDiscount;
                    $sellerDeliveryCharge = $deliveryCharges - $allocatedDelivery;
                    $sellerHandlingCharge = $handlingCharges - $allocatedHandling;
                    $sellerDonation = $donationAmount - $allocatedDonation;
                } else {

                    $ratio = $sellerSubtotal / $cartSubtotal;

                    $sellerDiscount = floor($ratio * $discountAmount);
                    $sellerDeliveryCharge = floor($ratio * $deliveryCharges);
                    $sellerHandlingCharge = floor($ratio * $handlingCharges);
                    $sellerDonation = floor($ratio * $donationAmount);

                    $allocatedDiscount += $sellerDiscount;
                    $allocatedDelivery += $sellerDeliveryCharge;
                    $allocatedHandling += $sellerHandlingCharge;
                    $allocatedDonation += $sellerDonation;
                }

                $currentIndex++;

                /* ==== FINAL TOTAL ==== */
                $sellerTotal = $sellerSubtotal + $sellerDeliveryCharge + $sellerHandlingCharge + $sellerDonation - $sellerDiscount;

                /* ==== DELIVERY COMMISSION ==== */
                $deliveryBoyCommission = ($deliveryCommissionType == 1) ? ($sellerTotal * $deliveryCommissionValue) / 100 : $deliveryCommissionValue;

                /* ==== SELLER COMMISSION ==== */
                $sellerCommission = ($sellerCommissionType == 1) ? ($sellerTotal * $sellerCommissionValue) / 100 : $sellerCommissionValue;

                /* ==== INSERT MAIN ORDER ==== */
                $orderId = Orders::insertGetId([
                    'group_order_id' => $groupOrderId,
                    'customer_id' => $request->customer_id,
                    'seller_id' => $sellerId,
                    'item_total' => $sellerSubtotal,
                    'delivery_charges' => $sellerDeliveryCharge,
                    'handling_charges' => $sellerHandlingCharge,
                    'donation_amount' => $sellerDonation,
                    'discount_amount' => $sellerDiscount,
                    'total_payable_amount' => $sellerTotal,
                    'promo_codes_id' => $promoCodeId,
                    'customer_address_id' => $request->customer_address_id,
                    'delivery_instructor_id' => $deliveryInstructor,
                    'payment_method' => $request->payment_method,
                    'payment_status' => $request->payment_status,
                    'order_status' => 1,
                    'is_assigned' => 0,
                    'order_notes' => $orderNotes,
                    'seller_commission' =>  round($sellerCommission),
                    'delivery_boy_commission' =>  round($deliveryBoyCommission),
                    'delivery_otp' => random_int(1000, 9999),
                ]);

                /* ==== ORDER NUMBER ==== */
                $orderNumber = 'ORD' . date('Y') . str_pad($orderId, 3, '0', STR_PAD_LEFT);

                /* ==== INSERT ITEMS ==== */
                foreach ($sellerItems as $val) {

                    OrdersItems::insert([
                        'group_order_id' => $groupOrderId,
                        'order_number' => $orderNumber,
                        'customer_id' => $request->customer_id,
                        'item_id' => $val['item_id'],
                        'seller_id' => $sellerId,
                        'variation' => json_encode($val['variation'] ?? []),
                        'item_price' => $val['unit_price'] ?? ($val['total_price'] / $val['quantity']),
                        'order_quantity' => $val['quantity'],
                        'order_quantity_amount' => $val['total_price'],
                    ]);

                    $item = Items::where('id', $val['item_id'])->first();

                    if ($item) {

                        $originalStock = $item->total_stock;

                        if ($originalStock >= $val['quantity']) {
                            $item->decrement('total_stock', $val['quantity']);
                            $item->refresh();

                            if ($item->total_stock <= 0) {

                                $item->status = 0; // 0 = Unavailable
                                $item->save();

                                // Notify only when stock becomes zero from positive
                                if ($originalStock > 0) {

                                    $notifyData = [
                                        'item_name' => $item->name,
                                        'item_id'   => $item->id,
                                    ];

                                    $this->SendStockOutNotification($seller, $notifyData);
                                }
                            }
                        }
                    }
                    AddToCart::where('item_id', $val['item_id'])->where('customer_id', $request->customer_id)->delete();
                }

                $this->common->OrderTrackingAdd($groupOrderId, 1);

                $sellerNotifyData = [
                    'group_order_id' => $groupOrderId,
                    'total_amount' => $sellerTotal,
                    'payment_status' => $request->payment_status,
                    'seller_name' => $seller['user_name'],
                    'customer_name' => $customer['user_name'],
                    'type' => 'send-seller',
                ];

                $this->SellerPlaceOrderMailNotification($seller, $sellerNotifyData);
            }

            $customer = Customer::find($request->customer_id);
            $notifyData = [
                'group_order_id' => $customerOrderIds,
                'order_status' => 1,
                'total_amount' => $request['total_payable_amount'],
                'payment_status' => $request->payment_status,
                'type' => 'send-customer',
                'customer_name' => $customer['user_name'],
            ];
            $this->CustomerPlaceOrderMailNotification($customer, $notifyData);

            return $this->common->API_Response(200, __('api_msg.order_placed'));
        } catch (\Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function SellerPlaceOrderMailNotification($seller, $array)
    {
        $Noti = $this->common->BasicNotiConfiguration('place-orders');

        if ($Noti['status'] == 1) {

            if ($Noti['send_mail'] == 1) {
                $this->common->Send_Mail($Noti['type'], $seller['email'], $array);
            }

            if ($Noti['send_notification'] == 1) {
                $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0,  $array['group_order_id'], $seller['device_token'], $seller['device_type'], $array);
            }
        }
    }
    public function CustomerPlaceOrderMailNotification($customer, $array)
    {
        $Noti = $this->common->BasicNotiConfiguration('place-orders');
        $array['notification_type'] = $Noti['type'];
        if ($Noti['status'] == 1) {
            if ($Noti['send_mail'] == 1) {
                $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
            }
            if ($Noti['send_notification'] == 1) {
                $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0,  implode(',', $array['group_order_id']), $customer['device_token'], $customer['device_type'], $array);
            }
        }
    }
    public function SendStockOutNotification($seller, $array)
    {
        $Noti = $this->common->BasicNotiConfiguration('product-stock-alert');

        if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
            $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0, $array['item_id'], $seller['device_token'], $seller['device_type'], $array);
        }
    }
    public function AddReview(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                    'content_id' => 'required|numeric',
                    'rating' => 'required|numeric',
                    'review' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $image = isset($request->image) ? $request->image : "";
            $review = Review::where('customer_id', $request->customer_id)->where('type', $request->type)->where('content_id', $request->content_id)->first();
            if ($review == null) {
                $insert = new Review();

                $insert->type = $request->type;
                $insert->customer_id = $request->customer_id;
                $insert->content_id = $request->content_id;
                $insert->review = $request->review;
                $insert->rating = $request->rating;
                $insert->image = $image;
                $insert->status = 1;

                if ($insert->save()) {
                    $item = Items::where('id', $request->content_id)->first();
                    $seller = Customer::where('id', $item['seller_id'])->first();

                    $array = [
                        'item_name' => $item['name'],

                    ];
                    $Noti = $this->common->BasicNotiConfiguration('reviews');

                    if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                        $this->common->SaveNotification('seller', $Noti['type'], 0, $seller['id'], 0, $item['id'], $seller['device_token'], $seller['device_type'], $array);
                    }
                    return $this->common->API_Response(200, __('api_msg.review_add_successfully'));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                $review->update([
                    'review' => $request->review,
                    'rating' => $request->rating,
                    'image' => $image,
                ]);
                return $this->common->API_Response(200, __('api_msg.review_updated_successfully'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetItemReviews(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $item_id = $request['item_id'];

            $data = Review::where('content_id', $item_id)->where('type', 1)->with('customer')->where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                foreach ($data as  $value) {
                    if ($value['image'] != null) {
                        $explode_value = explode(",", $value['image']);
                        $array = [];
                        foreach ($explode_value as  $item) {
                            $array[] = $this->common->getImage($this->folder_review, $item);
                        }
                        $value['image'] = implode(",", $array);
                    }

                    $customer = $value->customer;
                    $value['user_name'] = "";
                    $value['full_name'] = "";
                    $value['user_image'] = "";
                    if ($customer != null) {
                        $value['user_name'] = $customer->user_name;
                        $value['full_name'] = $customer->full_name;
                        $value['user_image'] = $this->common->getImage($this->folder_customer, $customer->image);
                    }
                    unset($value->customer);
                }
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetNotifications(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];

            $arr = NotificationTracking::where('type', 1)->where('type_id', $customer_id)->pluck('notification_id')->toArray();

            $data = Notification::whereNotIn('id', $arr)
                ->where(function ($query) use ($customer_id) {

                    $query->where(function ($q) use ($customer_id) {
                        $q->where('customer_id', $customer_id);
                    })
                        ->orWhere(function ($q) {
                            $q->where('customer_id', 0);
                        });
                })
                ->whereIn('type', [
                    'admin',
                    'new-product-arrival',
                    'new-special-offers',
                    'new-added-categories'
                ]);


            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ReadNotification(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                    'notification_id' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }


            $noti = new NotificationTracking();
            $noti->type = 1;
            $noti->type_id = $request['customer_id'];
            $noti->notification_id = $request['notification_id'];
            $noti->status = 1;

            if ($noti->save()) {
                return $this->common->API_Response(200, __('api_msg.notification_read'));
            } else {
                return $this->common->API_Response(400,  __('api_msg.notification_not_read'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ViewOrders(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'order_id' => 'required',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $order_id = $request['order_id'];
            $data = Orders::where('order_number', $order_id)->with('customer', 'customer_address', 'seller', 'item')->first();
            if (isset($data) && $data != null) {

                if ($data->customer != null) {
                    if ($data->customer != null) {
                        $data['customer_user_name'] = $data->customer['user_name'];
                        $data['customer_name'] = $data->customer['full_name'];
                        $data['customer_image'] = $this->common->getImage($this->folder_customer, $data->customer->image);
                    } else {
                        $data['customer_user_name'] = "";
                        $data['customer_name'] = "";
                        $data['customer_image'] = "";
                    }
                    if ($data->customer_address != null) {
                        $address = $data->customer_address;
                        $data['shipping_address'] = $address['address'] . "," . $address['area'] . "," . $address['city'] . "," . $address['state'] . "," . $address['country'] . "," . $address['pincode'];
                    } else {
                        $data['shipping_address'] = "";
                    }

                    if ($data->seller != null) {
                        $storeData = $this->common->SellerStoreInfo($data['seller_id']);

                        $data['seller_user_name'] = $data->seller['user_name'];
                        $data['seller_name'] = $data->seller['full_name'];
                        $data['seller_image'] = $this->common->getImage($this->folder_customer, $data->seller->image);
                        $data['store_name'] = $storeData['store_name'];
                        $data['store_logo'] = $storeData['store_logo'];
                    } else {
                        $data['seller_user_name'] = "";
                        $data['seller_name'] = "";
                        $data['seller_image'] = "";
                    }

                    if ($data->item != null) {
                        $data['item_name'] = $data->item['name'];
                        $data['item_image'] = $this->common->getImage($this->folder_item, $data->item->portrait_img);
                        $data['item_avg_rating'] = $this->common->avg_rating($data['item_id'], 1);
                    } else {
                        $data['item_name'] = "";
                        $data['item_image'] = "";
                        $data['item_avg_rating'] = "0.0";
                    }
                    $data['final_total'] = round($data['item_amount'] + $data['tax_amount'] + $data['delivery_charges'] - $data['discount_amount'], 2);

                    $data['delivery_instructor'] = $this->common->GetDeliveryInstructorByIds($data['delivery_instructor_id']);
                }
                unset($data->customer, $data->customer_address, $data->seller, $data->item);
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function DownloadInvoice(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                ],

            );

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $group_order_id = $request['group_order_id'];

            $params['orders'] = Orders::where('group_order_id', $group_order_id)->with('customer_address', 'customer')->first();
            if (!$params['orders']) {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
            $params['order_date'] = $this->common->GetDateTime($params['orders']['created_at']);

            $params['orders']->items = OrdersItems::where('group_order_id', $group_order_id)->with('item')->get();
            foreach ($params['orders']->items as $key => $value) {
                $item = $value->item;
                $value['item_name'] = $item?->name ?? '';
                $value['item_image'] = $item ? $this->common->getImage($this->folder_item, $item['portrait_img']) : '';
                $value['item_per_unit'] = $item ? ($item->per_unit . $item->unit) : '';
            }
            $params['delivery_instructor'] = $this->common->GetDeliveryInstructorByIds($params['orders']['delivery_instructor_id']);

            $this->common->DeleteOlderReceipt();

            $pdf = Pdf::loadView('invoice', ['result' => $params])->setPaper([0, 0, 700, 890]);

            $pdfName = config('app.name') . "-Invoice_-" . $group_order_id . ".pdf";
            $pdfPath = 'invoice/' . $pdfName;
            Storage::disk('public')->put($pdfPath, $pdf->output());
            $pdfUrl = $this->common->getImage('invoice',  $pdfName);

            return $this->common->API_Response(200, __('api_msg.download_invoice'), ['pdf_url' => $pdfUrl]);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function ImageUpload(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [

                    'type' => 'required|numeric',
                    'image' => 'image|mimes:jpeg,png,jpg|max:2048',

                ],
                [
                    'type.required' => __('api_msg.type_is_required'),

                ]
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request['type'];
            if (isset($request->image) && $request->image != 'undefined') {
                $file = $request->file('image');

                if ($type == 1) { // Review
                    $folder = $this->folder_review;
                }
                $file_name = $this->common->saveImage($file, $folder, $folder . '_');

                if (isset($file_name)) {
                    $result = $file_name;
                    return $this->common->API_Response(200, __('api_msg.image_upload_success'), $result);
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'), []);
                }
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function TrackIntrest(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required',
                    'item_id' => 'required',
                ]
            );

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $customer_id = $request['customer_id'];
            $item_id = $request['item_id'];

            $existing = TrackIntrest::where('customer_id', $customer_id)
                ->where('item_id', $item_id)
                ->first();

            if ($existing) {
                $existing->count = $existing->count + 1;
                $existing->save();

                return $this->common->API_Response(200, __('api_msg.data_updated'));
            } else {
                $item = Items::find($item_id);

                $insert = [
                    'customer_id' => $customer_id,
                    'item_id' => $item_id,
                    'category_id' => $item->category_id,
                    'sub_category_id' => $item->sub_category_id,
                    'count' => 1,
                    'status' => 1,
                ];

                TrackIntrest::create($insert);

                return $this->common->API_Response(200, __('api_msg.data_added'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddWalletAmount(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                    'amount' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];
            $amount = $request['amount'];
            $transaction_id = isset($request->transaction_id) ? $request->transaction_id : 'TXN' . time() . rand(1000, 9999);

            $insert['type'] = 1;
            $insert['customer_id'] = $customer_id;
            $insert['delivery_boy_id'] = 0;
            $insert['group_order_id'] = "";
            $insert['transaction_id'] = $transaction_id;
            $insert['price'] = $amount;
            $insert['status'] = 1;
            $result = Transaction::insertGetId($insert);

            if (isset($result) && $result > 0) {
                Customer::where('id', $request['customer_id'])->increment('wallet_amount', $amount);
                $customer = Customer::where('id', $request['customer_id'])->first();

                $Noti = $this->common->BasicNotiConfiguration(19);
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Wallet Amount Added 💰 ";
                    $message = "Good news!" . " " . $amount . " " . "has been added to your wallet. Your updated balance is" . " " . $customer['wallet_amount'];
                    $this->common->SaveNotification('user', 10, $title, $message, 0, 0, $customer['id'], 0, "", "", "", $customer['device_token'], $customer['device_type']);
                }

                return $this->common->API_Response(200, __('api_msg.add_wallet_amount'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetTransactionList(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];

            $data = Transaction::where('type', 1)->where('customer_id', $customer_id)->where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function AddRemoveGSTNumber(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',

                ],

            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request['customer_id'];

            $updateData = [
                'gst_number' => $request['gst_number'] ?? "",
                'gst_firm_name' => $request['gst_firm_name'] ?? "",
            ];
            $updated = Customer::where('id', $customer_id)->update($updateData);

            if ($updated) {
                return $this->common->API_Response(200, __('api_msg.gst_details_update'));
            } else {
                return $this->common->API_Response(400, __('api_msg.gst_details_not_update'));
            }

            return $this->common->API_Response(200, __('api_msg.data_edit_succefully'));
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetDeliveryInstructor(Request $request)
    {
        try {
            try {
                $data = DeliveryInstructor::where('status', 1)->latest()->get();

                if (sizeof($data) > 0) {
                    $this->common->imageNameToUrl($data, 'image', $this->folder_delivery_instructor);
                }

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } catch (Exception $e) {
                return $this->common->API_Error($e);
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    // UnUsed Apis
    public function ManageOrdersItems(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'item_details' => 'required',
                ],
                [
                    'item_details.required' => __('api_msg.item_details_is_required'),
                ]
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $decodeItems = json_decode($request['item_details'], true);

            $totalPrice = 0;
            $totalTaxAmount = 0;
            foreach ($decodeItems as $value) {
                $totalPrice += $value['total_price'];
                $totalTaxAmount += $value['tax_amount'];
            }
            $finalPrice = $totalPrice + $totalTaxAmount;

            return [
                'total_price' => $totalPrice,
                'total_tax_amount' => $totalTaxAmount,
                'final_price' => $finalPrice,
            ];
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetArticle()
    {
        try {

            $data = Article::where('status', 1);

            $total_rows = $data->count();
            $total_page = $this->page_limit;
            $page_size = ceil($total_rows / $total_page);
            $current_page = $request['page_no'] ?? 1;
            $offset = $current_page * $total_page - $total_page;

            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            $data->take($total_page)->offset($offset);
            $data = $data->latest()->get();

            if (count($data) > 0) {

                $this->common->imageNameToUrl($data, 'image', $this->folder_article);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data, $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetReason(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'type' => 'required|numeric',
                ],
                [
                    'type.required' => __('api_msg.type_is_required'),
                ]
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $type = $request['type'];

            $data = Reason::where('type', $type)->latest()->get();
            if (count($data) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $data);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function OrdersCancel(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'industries_id' => 'required|numeric',
                    'order_id' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                    'reason_id' => 'required|numeric',
                ],
                [
                    'industries_id.required' => __('api_msg.industries_id_is_required'),
                    'order_id.required' => __('api_msg.order_id_is_required'),
                    'customer_id.required' => __('api_msg.customer_id_is_required'),
                    'reason_id.required' => __('api_msg.reason_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $industries_id = $request['industries_id'];
            $order_id = $request['order_id'];
            $customer_id = $request['customer_id'];
            $reason_id = $request['reason_id'];

            $insert = new OrderCancel();
            $insert->industries_id = $industries_id;
            $insert->customer_id = $customer_id;
            $insert->order_id = $order_id;
            $insert->reason_id = $reason_id;
            $insert->status = 0;

            if ($insert->save()) {
                $Noti = $this->common->BasicNotiConfiguration(10);

                //Customer
                $customer = Customer::where('id', $customer_id)->first();
                $orders = Orders::where('id', $order_id)->first();
                $seller = Customer::where('id', $orders['seller_id'])->first();
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {

                    $this->common->Send_Mail(16, $customer['email']);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Order Cancellation Requested";
                    $message = "Your cancellation request has been received. Please wait for admin approval.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $orders['order_number'], "", "", $customer['device_token'], $customer['device_type']);
                }
                //Seller
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $array = [
                        'customer_name' => $customer['full_name'],
                        'orders_id' => $orders['order_number'],
                    ];
                    $this->common->Send_Mail(17, $customer['email'], $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "New Order Cancellation Request Received";
                    $message = $customer['full_name'] . " " . "has requested to cancel Order No #:-" . $orders['order_number'] . "Please approve or reject the request.";
                    $this->common->SaveNotification('user', 5, $title, $message, $seller['id'], 0, $customer['id'], 0, $orders['order_number'], "", "", $seller['device_token'], $seller['device_type']);
                }
                return $this->common->API_Response(200, __('api_msg.order_cancel'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function OrdersReturn(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'industries_id' => 'required|numeric',
                    'order_id' => 'required|numeric',
                    'customer_id' => 'required|numeric',
                    'reason_id' => 'required|numeric',
                ],
                [
                    'industries_id.required' => __('api_msg.industries_id_is_required'),
                    'order_id.required' => __('api_msg.order_id_is_required'),
                    'customer_id.required' => __('api_msg.customer_id_is_required'),
                    'reason_id.required' => __('api_msg.reason_id_is_required'),
                ]
            );
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $industries_id = $request['industries_id'];
            $order_id = $request['order_id'];
            $customer_id = $request['customer_id'];
            $reason_id = $request['reason_id'];

            $orders = Orders::where('id', $order_id)->first();
            $insert = new OrderCancel();
            $insert->industries_id = $industries_id;
            $insert->customer_id = $customer_id;
            $insert->order_id = $order_id;
            $insert->item_id = $orders['item_id'];
            $insert->reason_id = $reason_id;
            $insert->status = 0;

            if ($insert->save()) {
                $Noti = $this->common->BasicNotiConfiguration(12);

                //Customer
                $customer = Customer::where('id', $customer_id)->first();
                $orders = Orders::where('id', $order_id)->first();
                $seller = Customer::where('id', $orders['seller_id'])->first();
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $array = [
                        'orders_id' => $orders['order_number'],
                    ];
                    $this->common->Send_Mail(20, $customer['email'], $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Return Request Received";
                    $message = "We've received your return request for Order ID #: " . $orders['order_number'] . " " . "Our team will review it and update you shortly.";
                    $this->common->SaveNotification('user', 5, $title, $message, 0, 0, $customer['id'], 0, $orders['order_number'], "", "", $customer['device_token'], $customer['device_type']);
                }

                //Seller
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $array = [
                        'customer_name' => $customer['full_name'],
                        'orders_id' => $orders['order_number'],
                    ];
                    $this->common->Send_Mail(17, $customer['email'], $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Orders Return Request Received";
                    $message = "A return request has been submitted by the customer for Order ID #:" . $orders['order_number'] . " " . "Please review and take necessary action";
                    $this->common->SaveNotification('user', 5, $title, $message, $seller['id'], 0, $customer['id'], 0, $orders['order_number'], "", "", $seller['device_token'], $seller['device_type']);
                }
                return $this->common->API_Response(200, __('api_msg.order_returned'));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function GetUpComingWithPastOrders(Request $request)
    {
        try {

            $validation = Validator::make(
                $request->all(),
                [
                    'customer_id' => 'required|numeric',
                ],
                [
                    'customer_id.required' => __('api_msg.customer_id_is_required'),
                ]
            );

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }
            $customer_id = $request->customer_id;

            // Common Pagination Setup
            $total_page = $this->page_limit;
            $current_page = $request['page_no'] ?? 1;
            $offset = ($current_page - 1) * $total_page;

            $upcomingStatuses = [1, 2, 3, 4];
            $pastStatuses = [5, 6, 7, 8, 9, 10];

            // Fetch all upcoming & past orders first
            $upcomingAll = Orders::where('customer_id', $customer_id)
                ->whereIn('order_status', $upcomingStatuses)
                ->orderBy('created_at', 'desc');

            $pastAll = Orders::where('customer_id', $customer_id)
                ->whereIn('order_status', $pastStatuses)
                ->orderBy('created_at', 'desc');

            // Count for pagination
            $total_rows = $upcomingAll->count() + $pastAll->count();
            $page_size = ceil($total_rows / $total_page);
            $more_page = $this->common->more_page($current_page, $page_size);
            $pagination = $this->common->pagination_array($total_rows, $page_size, $current_page, $more_page);

            // Get paginated upcoming orders
            $upcomingOrders = $upcomingAll->skip($offset)->take($total_page)->get()
                ->groupBy('group_order_id')
                ->map(function ($orders) {
                    return [
                        'group_order_id' => $orders[0]->group_order_id,
                        'orders' => $orders,
                    ];
                })->values();

            // If we still have space in limit, fetch from past
            $remaining = $total_page - $upcomingOrders->count();
            $pastOrders = collect();

            if ($remaining > 0) {
                $pastOrders = $pastAll->skip($offset - $upcomingAll->count())->take($remaining)->get()
                    ->groupBy('group_order_id')
                    ->map(function ($orders) {
                        return [
                            'group_order_id' => $orders[0]->group_order_id,
                            'orders' => $orders,
                        ];
                    })->values();
            }

            // Common loop to enhance all orders
            $enhanceOrders = function ($groupedOrders) {
                foreach ($groupedOrders as &$group) {
                    $this->common->CommonOrderDetails($group['orders']);
                }
                return $groupedOrders;
            };

            // Apply enhancements
            $upcomingOrders = $enhanceOrders($upcomingOrders);
            $pastOrders = $enhanceOrders($pastOrders);

            // Final return
            if ($upcomingOrders->isNotEmpty() || $pastOrders->isNotEmpty()) {
                $combinedData = [
                    'upcoming_orders' => $upcomingOrders,
                    'past_orders' => $pastOrders,
                ];
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), [$combinedData], $pagination);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'), [], $pagination);
            }
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }
    }
    public function OrdersDetails(Request $request)
    {
        try {
            $validation = Validator::make(
                $request->all(),
                [
                    'group_order_id' => 'required',
                ],
                [
                    'group_order_id.required' => __('api_msg.group_order_id_is_required'),
                ]
            );

            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $group_order_id = $request['group_order_id'];

            $orders = Orders::where('group_order_id', $group_order_id)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => __('api_msg.data_not_found'),
                    'result' => [],
                ]);
            }

            $result = [];
            $item_total = 0;
            $tax_total = 0;
            $delivery_total = 0;
            $discount_total = 0;
            $order_status = 0;
            $payment_method = 0;
            $payment_status = 0;
            $shipping_address = "";


            foreach ($orders as $value) {
                $address = $value->customer_address;

                $order_data = [
                    'group_order_id' => $value->group_order_id,
                    'order_number' => $value->order_number,
                    'item_name' => $value->item ? $value->item['name'] : '',
                    'item_image' => $value->item ? $this->common->getImage($this->folder_item, $value->item->portrait_img) : '',
                    'order_quantity' => $value->order_quantity ? $value->order_quantity : 0,
                    'variation' => $value->variation ? $value->variation : '',
                    'item_amount' => $value->item_amount ? $value->item_amount : 0,
                ];
                $order_status = $value->order_status;
                $payment_method = $value->payment_method;
                $payment_status = $value->payment_status;
                $shipping_address = $address->address . "," . $address->area . "," . $address->city . ","  . $address->state . "," . $address->area . ","  . $address->country . ","  . $address->pincode;

                $item_total += $value->item_amount;
                $tax_total += $value->tax_amount;
                $delivery_total += $value->delivery_charges;
                $discount_total += $value->discount_amount;

                $result[] = $order_data;
            }
            $order_detail = [
                'order_status' => $order_status,
                'payment_method' => $payment_method,
                'payment_status' => $payment_status,
                'shipping_address' => $shipping_address,

            ];
            $summary = [
                'item_total' => round($item_total, 2),
                'tax_amount' => round($tax_total, 2),
                'delivery_charges' => round($delivery_total, 2),
                'discount_amount' => round($discount_total, 2),
                'final_total' => round(($item_total + $tax_total + $delivery_total - $discount_total), 2),
            ];

            return response()->json([
                'status' => 200,
                'message' => "Data Received Successfully.",
                'result' => $result,
                'order_detail' => $order_detail,
                'summary' => $summary,
            ]);
        } catch (Exception $e) {
            return $this->common->API_Error($e);
        }

        // try {
        //     $validation = Validator::make(
        //         $request->all(),
        //         [
        //             'group_order_id' => 'required',
        //         ],
        //         [
        //             'group_order_id.required' => __('api_msg.group_order_id_is_required'),
        //         ]
        //     );

        //     if ($validation->fails()) {
        //         return $this->common->API_Response(400, $validation->errors()->first());
        //     }
        //     $group_order_id = $request['group_order_id'];
        //     $orders = Orders::where('group_order_id', $group_order_id)
        //         ->orderBy('created_at', 'asc')
        //         ->get();

        //     if ($orders->isEmpty()) {
        //         return $this->common->API_Response(400, __('api_msg.data_not_found'), []);
        //     }

        //     $result = [];
        //     $item_total = 0;
        //     $tax_total = 0;
        //     $delivery_total = 0;
        //     $discount_total = 0;

        //     foreach ($orders as $value) {
        //         // Prepare order details
        //         $order_data = [
        //             'order_id' => $value->id,
        //             'item_name' => $value->item ? $value->item['name'] : '',
        //             'item_image' => $value->item ? $this->common->getImage($this->folder_item, $value->item->portrait_img) : '',
        //             'final_total' => round(
        //                 $value->item_amount + $value->tax_amount + $value->delivery_charges - $value->discount_amount,
        //                 2
        //             ),
        //             'customer_name' => $value->customer ? $value->customer['full_name'] : '',
        //             'customer_image' => $value->customer ? $this->common->getImage($this->folder_customer, $value->customer->image) : '',
        //             'seller_name' => $value->seller ? $value->seller['full_name'] : '',
        //             'seller_image' => $value->seller ? $this->common->getImage($this->folder_customer, $value->seller->image) : ''
        //         ];

        //         // Accumulate for summary
        //         $item_total += $value->item_amount;
        //         $tax_total += $value->tax_amount;
        //         $delivery_total += $value->delivery_charges;
        //         $discount_total += $value->discount_amount;

        //         $result[] = $order_data;
        //     }

        //     // Prepare the summary
        //     $summary = [
        //         'item_total' => round($item_total, 2),
        //         'tax_amount' => round($tax_total, 2),
        //         'delivery_charges' => round($delivery_total, 2),
        //         'discount_amount' => round($discount_total, 2),
        //         'final_total' => round(($item_total + $tax_total + $delivery_total - $discount_total), 2),
        //     ];

        //     // Return the response with both the result and summary
        //     return $this->common->API_Response(200, "Data Received Successfully.", [
        //         'result' => $result, // 'result' contains the orders data directly
        //         'summary' => $summary  // 'summary' contains the totals
        //     ]);
        // } catch (Exception $e) {
        //     return $this->common->API_Error($e);
        // }
    }
}
