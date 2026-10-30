<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Common;
use App\Models\Customer;
use App\Models\DeliveryAssignment;
use App\Models\Items;
use App\Models\ItemsImages;
use App\Models\Orders;
use App\Models\Review;
use App\Models\SellerRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Exception;

class SellerController extends Controller // Login Type : 1- OTP, 2- Google, 3- Apple, 4- Normal
{
    private $folder = "customer";
    private $folder_product = "product";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $input_search = $request['input_search'];
            $input_type = $request['input_type'];
            $input_login_type = $request['input_login_type'];

            $query = Customer::where('is_seller', 1)->latest();

            if ($input_search != null && isset($input_search)) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('user_name', 'LIKE', "%{$input_search}%")->orWhere('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%")->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                });
            }
            if ($input_login_type != "all" && $input_login_type != null) {
                $query->where('type', $input_login_type);
            }
            if ($input_type == "today" && $input_type != null) {
                $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            } elseif ($input_type == "month" && $input_type != null) {
                $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
            } elseif ($input_type == "year" && $input_type != null) {
                $query->whereYear('created_at', date('Y'));
            }

            $params['data'] = $query->orderBy('id', 'DESC')->paginate(15);
            $this->common->imageNameToUrl($params['data'], 'image', $this->folder);

            foreach ($params['data'] as $value) {
                $seller_id = $value->id;
                $value['avg_rating'] = $this->common->SellerAvgRating($seller_id);
                $value['total_sales'] = $this->common->SellerTotalSales($seller_id);
            }

            return view('admin.seller.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {

            return view('admin.seller.add');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_customer|email',
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    Rule::unique('tbl_customer')->where(function ($query) use ($request) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number);
                    }),
                ],
                'password' => 'required|min:4',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'store_name' => 'required',
                'store_address' => 'required',
                'bank_proof' => 'image|mimes:jpeg,png,jpg|max:5120',
                'store_logo' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $seller = new Customer();

            if (isset($request['image'])) {
                $files = $request['image'];
                $seller['image'] = $this->common->saveImage($files, $this->folder, 'seller_');
            }
            $email_array = explode('@', $request['email']);
            $seller['user_name'] = $this->common->user_name($email_array[0]);
            $seller['full_name'] = $request['full_name'];
            $seller['email'] = $request['email'];
            $seller['password'] = Hash::make($request['password']);
            $seller['country_code'] = $request['country_code'];
            $seller['mobile_number'] = $request['mobile_number'];
            $seller['country_name'] = $request['country_name'];
            $seller['is_seller'] = 1;
            $seller['type'] = 4;
            $seller['device_type'] = 0;
            $seller['device_token'] = "";
            $seller['gst_number'] = "";
            $seller['gst_firm_name'] = "";
            $seller['printer_mac_address'] = $request['printer_mac_address'] ?? "";
            $seller['status'] = 1;

            if ($seller->save()) {

                $otherInfo = new SellerRequest();
                $otherInfo['customer_id'] = $seller['id'];
                $otherInfo['store_name'] = $request['store_name'];
                $otherInfo['store_url'] = $request['store_url'] ?? "";
                $otherInfo['store_address'] = $request['store_address'] ?? "";
                $otherInfo['bank_name'] = $request['bank_name'] ?? "";
                $otherInfo['bank_holder_name'] = $request['bank_holder_name'] ?? "";
                $otherInfo['account_no'] = $request['account_no'] ?? "";
                $otherInfo['ifsc_code'] = $request['ifsc_code'] ?? "";
                $otherInfo['bank_address'] = $request['bank_address'] ?? "";
                $otherInfo['status'] = 1;

                if (isset($request['store_logo'])) {
                    $files1 = $request['store_logo'];
                    $otherInfo['store_logo'] = $this->common->saveImage($files1, $this->folder, 'store_');
                }
                if (isset($request['bank_proof'])) {
                    $files2 = $request['bank_proof'];
                    $otherInfo['bank_proof'] = $this->common->saveImage($files2, $this->folder, 'bank_proof_');
                } else {
                    $otherInfo['bank_proof'] = "";
                }

                if ($otherInfo->save()) {
                    return response()->json(array('status' => 200, 'success' => __('label.success_add_seller')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.error_add_seller')));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_seller')));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {
            $params['result'] = Customer::where('id', $id)->first();
            $params['otherData'] = SellerRequest::where('customer_id', $id)->first();

            if (isset($params['result']['id'])) {

                $this->common->imageNameToUrl(array($params['result']), 'image', $this->folder);
                $this->common->imageNameToUrl(array($params['otherData']), 'store_logo', $this->folder);

                return view('admin.seller.edit', $params);
            } else {
                return redirect()->back()->with('error', __('label.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Customer::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();

                $otherData = SellerRequest::where('customer_id', $id)->first();
                if (isset($otherData)) {
                    $this->common->deleteImageToFolder($this->folder, $otherData['bank_proof']);
                    $this->common->deleteImageToFolder($this->folder, $otherData['store_logo']);
                    $otherData->delete();
                }

                Customer::SellerDeleteAllData($id);
            }

            return redirect()->route('admin.seller.index')->with('success', __('label.seller_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_customer,email,' . $id,
                'country_code' => 'required',
                'country_name' => 'required',
                'mobile_number' => [
                    'required',
                    'numeric',
                    'digits:10',
                    Rule::unique('tbl_customer')->where(function ($query) use ($request, $id) {
                        return $query->where('country_code', $request->country_code)
                            ->where('mobile_number', $request->mobile_number)
                            ->where('id', '!=', $id);
                    }),
                ],
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
                'store_name' => 'required',
                'store_address' => 'required',
                'bank_proof' => 'image|mimes:jpeg,png,jpg|max:5120',
                'store_logo' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $seller = Customer::where('id', $request['id'])->first();

            if (isset($seller['id'])) {
                if (isset($request['image'])) {
                    $files = $request['image'];
                    $seller['image'] = $this->common->saveImage($files, $this->folder, 'seller_');
                    $this->common->deleteImageToFolder($this->folder, basename($request->old_image));
                }

                $seller['full_name'] = $request['full_name'];
                $seller['email'] = $request['email'];
                if ($request['password'] != null && isset($request['password'])) {
                    $seller['password'] = Hash::make($request['password']);
                }

                $seller['country_code'] = $request['country_code'];
                $seller['mobile_number'] = $request['mobile_number'];
                $seller['country_name'] = $request['country_name'];
                $seller['printer_mac_address'] = $request['printer_mac_address'] ?? "";

                if ($seller->save()) {

                    $otherInfo = SellerRequest::where('customer_id', $request['id'])->first();

                    $otherInfo['store_name'] = $request['store_name'];
                    $otherInfo['store_url'] = $request['store_url'] ?? "";
                    $otherInfo['store_address'] = $request['store_address'] ?? "";


                    if (isset($request['store_logo'])) {
                        $files2 = $request['store_logo'];
                        $otherInfo['store_logo'] = $this->common->saveImage($files2, $this->folder, 'store_');
                        $this->common->deleteImageToFolder($this->folder, basename($request->old_store_logo));
                    }

                    // $otherInfo['bank_name'] = $request['bank_name'] ?? "";
                    // $otherInfo['bank_holder_name'] = $request['bank_holder_name'] ?? "";
                    // $otherInfo['account_no'] = $request['account_no'] ?? "";
                    // $otherInfo['ifsc_code'] = $request['ifsc_code'] ?? "";
                    // $otherInfo['bank_address'] = $request['bank_address'] ?? "";

                    // if (isset($request['bank_proof'])) {
                    //     $files1 = $request['image'];
                    //     $otherInfo['bank_proof'] = $this->common->saveImage($files1, $this->folder, 'bank_proof_');
                    //     $this->common->deleteImageToFolder($this->folder, basename($request->old_bank_proof));
                    // }

                    if ($otherInfo->save()) {
                        return response()->json(array('status' => 200, 'success' => __('label.success_edit_seller')));
                    } else {
                        return response()->json(array('status' => 400, 'errors' => __('label.error_edit_seller')));
                    }
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.error_edit_seller')]);
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_seller')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ChangeStatus($id)
    {
        try {
            $data = Customer::where('id', $id)->first();
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
    public function SellerProfile($seller_id, Request $request)
    {
        try {
            $params['seller_id'] = $seller_id;
            $params['brand'] = Brand::where('status', 1)->latest()->get();

            //Seller Data
            $params['result'] = Customer::where('id', $seller_id)->first();
            $this->common->imageNameToUrl(array($params['result']), 'image', $this->folder);
            $params['info'] = SellerRequest::where('customer_id', $seller_id)->first();
            $this->common->imageNameToUrl(array($params['info']), 'store_logo', $this->folder);

            $params['result']['avg_rating'] = $this->common->SellerAvgRating($seller_id);
            $params['result']['total_sales'] = $this->common->SellerTotalSales($seller_id);

            //Product Data

            $input_search = $request['input_search'];
            $input_brand = $request['input_brand_id'];


            $params['TotalProduct'] = Items::where('seller_id', $seller_id)->count();
            $query = Items::where('seller_id', $seller_id);

            if ($input_search != null && isset($input_search)) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('name', 'LIKE', "%{$input_search}%")->orWhere('sort_description', 'LIKE', "%{$input_search}%");
                });
            }
            if ($input_brand !== 'all' && $input_brand != null) {
                $query->where('brand_id', $input_brand);
            }

            $params['product'] = $query->orderBy('id', 'DESC')->paginate(24);
            $this->common->imageNameToUrl($params['product'], 'portrait_img', $this->folder_product);

            return view('admin.seller.profile', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ProductStatusChange($product_id)
    {

        try {
            $data = Items::where('id', $product_id)->first();
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
    public function ProductDestroy($product_id)
    {
        try {
            $data = Items::where('id', $product_id)->first();

            $this->common->deleteImageToFolder($this->folder, $data['portrait_img']);
            $this->common->deleteImageToFolder($this->folder, $data['landscape_img']);
            $this->common->deleteImageToFolder($this->folder, $data['url']);
            $data->delete();

            $other_image = ItemsImages::where('item_id', $product_id)->get();
            if (isset($other_image)) {
                foreach ($other_image as $value) {
                    $this->common->deleteImageToFolder($this->folder_product, $value->image);
                    $value->delete();
                }
            }
            $items_attributes = ItemsImages::where('item_id', $product_id)->get();
            if (isset($items_attributes)) {
                foreach ($items_attributes as $item) {
                    $this->common->deleteImageToFolder($this->folder_product, $item->option);
                    $item->delete();
                }
            }
            return redirect()->back()->with('success', __('label.product_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    //OrderInfo
    public function OrderList($seller_id, Request $request)
    {
        $params['seller_id'] = $seller_id;

        $params['data'] = [];
        $params['vehicle'] = Vehicle::where('is_used', 0)->where('status', 1)->latest()->get();

        $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();
        $params['item'] = Items::where('seller_id', $seller_id)->where('status', 1)->latest()->get();

        if ($request->ajax()) {

            $input_search = $request['input_search'];
            $input_payment_method = $request['input_payment_method'];
            $input_item = $request['input_item_id'];
            $input_customer = $request['input_customer_id'];
            $input_status = $request['input_status'];

            $query = Orders::where('seller_id', $seller_id)->with('customer', 'item');

            if (!empty($input_search)) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('group_order_id', 'LIKE', "%{$input_search}%")->orWhere('order_number', 'LIKE', "%{$input_search}%")->orWhere('order_notes', 'LIKE', "%{$input_search}%");
                });
            }

            if ($input_payment_method !== 'all') {
                $query->where('payment_method', $input_payment_method);
            }
            if ($input_item !== 'all') {
                $query->where('item_id', $input_item);
            }

            if ($input_customer !== 'all') {
                $query->where('customer_id', $input_customer);
            }
            if ($input_status !== 'all') {
                $query->where('order_status', $input_status);
            }

            $data = $query->latest()->get();

            return DataTables()::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<div class="d-flex justify-content-around">';
                    $btn .= '<a href="' . route('admin.order.view', [$row->order_number]) . '" class="edit-delete-btn">';
                    $btn .= '<i class="fa-solid fa-eye fa-xl"></i>';
                    $btn .= '</a>';
                    $btn .= '</div>';

                    return $btn;
                })
                ->addColumn('group_action', function ($row) {
                    $view_orders = __('label.view_orders');
                    $view_order = __('label.view_order');
                    $btn = '<div class="d-flex justify-content-around">';

                    if (!in_array($row->order_status, [1, 4, 5, 6, 8, 9, 10])) {
                        $assignDelivery = DeliveryAssignment::where('order_id', $row->group_order_id)->first();
                        if ($assignDelivery) {
                            $btn .= '<a class="edit-delete-btn delivery_assign" data-toggle="modal" href="#EditModel"';
                            $btn .= ' data-order_id="' . $assignDelivery->order_id . '"';
                            $btn .= ' data-id="' . $assignDelivery->id . '"';
                            $btn .= ' data-delivery_boy_id="' . $assignDelivery->delivery_boy_id . '"';
                            $btn .= ' data-vehicle_id="' . $assignDelivery->vehicle_id . '"';
                            $btn .= ' data-pickup_time="' . $assignDelivery->pickup_time . '"';
                            $btn .= ' data-pickup_location="' . $assignDelivery->pickup_location . '"';
                            $btn .= ' data-return_pickup_time="' . $assignDelivery->return_pickup_time . '"';
                            $btn .= ' data-delivery_status="' . $assignDelivery->delivery_status . '"';
                            $btn .= ' data-notes="' . $assignDelivery->notes . '">';
                        } else {

                            $btn .= '<a class="edit-delete-btn delivery_assign" data-toggle="modal" href="#EditModel"';
                            $btn .= ' data-order_id="' . $row->group_order_id . '"';
                            $btn .= ' data-id=""';
                            $btn .= ' data-delivery_boy_id=""';
                            $btn .= ' data-vehicle_id=""';
                            $btn .= ' data-pickup_time=""';
                            $btn .= ' data-pickup_location=""';
                            $btn .= ' data-return_pickup_time=""';
                            $btn .= ' data-delivery_status=""';
                            $btn .= ' data-notes="">';
                        }

                        $btn .= '<i class="fa-solid fa-clipboard-check fa-xl"></i>';
                        $btn .= '</a>';
                    }

                    $btn .= '<a href="' . route('admin.order.edit', [$row->group_order_id]) . '" title="' . $view_orders . '">';
                    $btn .= '<span class="order-badge order-badge-info ml-3">' . $view_order . '</span>';
                    $btn .= '</a>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->addColumn('status', function ($row) {
                    $pending = __('label.pending');
                    $confirmed = __('label.confirmed');
                    $shipped = __('label.shipped');
                    $out_for_delivery = __('label.out_for_delivery');
                    $delivered = __('label.delivered');
                    $cancelled = __('label.cancelled');
                    $return_requested = __('label.return_requested');
                    $return_inprocess = __('label.return_inprocess');
                    $returned = __('label.returned');
                    $returnedrejected = __('label.returnedrejected');
                    $btn = '<div class="d-flex justify-content-around">';

                    if ($row->order_status == 1) {
                        $btn .= '<span class="order-badge order-badge-gray mr-1">' . $pending . '</span>';
                    } else if ($row->order_status == 2) {
                        $btn .= '<span class="order-badge order-badge-blue mr-1">' . $confirmed . '</span>';
                    } else if ($row->order_status == 3) {
                        $btn .= '<span class="order-badge order-badge-orange mr-1">' . $shipped . '</span>';
                    } else if ($row->order_status == 4) {
                        $btn .= '<span class="order-badge order-badge-purple mr-1">' . $out_for_delivery . '</span>';
                    } else if ($row->order_status == 5) {
                        $btn .= '<span class="order-badge order-badge-green mr-1">' . $delivered . '</span>';
                    } else if ($row->order_status == 6) {
                        $btn .= '<span class="order-badge order-badge-red mr-1">' . $cancelled . '</span>';
                    } else if ($row->order_status == 7) {
                        $btn .= '<span class="order-badge order-badge-yellow mr-1">' . $return_requested . '</span>';
                    } else if ($row->order_status == 8) {
                        $btn .= '<span class="order-badge order-badge-light-blue mr-1">' . $return_inprocess . '</span>';
                    } else if ($row->order_status == 9) {
                        $btn .= '<span class="order-badge order-badge-dark-green mr-1">' . $returned . '</span>';
                    } else if ($row->order_status == 10) {
                        $btn .= '<span class="order-badge order-badge-dark-orange mr-1">' . $returnedrejected . '</span>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['action', 'status', 'group_action'])
                ->make(true);
        }

        return view('admin.seller.orderlist', $params);
    }
    public function ReviewList($seller_id, Request $request)
    {
        $params['seller_id'] = $seller_id;

        $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();


        if ($request->ajax()) {
            $input_search = $request['input_search'];
            $input_customer = $request['input_customer_id'];

            $query = Review::where('content_id', $seller_id)->where('type', 2)->with('customer');

            if ($input_search) {
                $query->where(function ($q) use ($input_search) {
                    $q->where('review', 'LIKE', "%{$input_search}%");
                });
            }

            if ($input_customer !== 'all' && isset($input_customer) != null) {
                $query->where('customer_id', $input_customer);
            }
            $data = $query->get();

            return DataTables()::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $activeLabel = __('label.show');
                    $inactiveLabel = __('label.hide');

                    $statusBtn = '';

                    if ($row->status == 1) {
                        $statusBtn = "<button type='button' id='{$row->id}' onclick='review_change_status({$row->id})' class='show-btn'>{$activeLabel}</button>";
                    } else {
                        $statusBtn = "<button type='button' id='{$row->id}' onclick='review_change_status({$row->id})' class='hide-btn'>{$inactiveLabel}</button>";
                    }

                    return $statusBtn;
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('admin.seller.review', $params);
    }
    public function ReviewStatusChange($id)
    {

        try {

            $data = Review::where('id', $id)->first();
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
