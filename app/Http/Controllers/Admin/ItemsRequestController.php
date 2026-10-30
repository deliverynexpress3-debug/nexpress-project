<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Items;
use App\Models\ItemsAttributes;
use App\Models\ItemsImages;
use Illuminate\Http\Request;
use Exception;


class ItemsRequestController extends Controller
{
    private $folder = "product";
    private $folder_attributes = "attributes";
    private $folder_seller = "customer";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            $params['brand'] = Brand::where('status', 1)->latest()->get();

            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_type = $request['input_status_type'];
                $input_seller = $request['input_seller_id'];
                $input_brand = $request['input_brand_id'];

                $query = Items::where('is_approved', 0)->with('brand', 'category', 'seller')->latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%")
                            ->orWhere('sort_description', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_type !== 'all') {
                    $query->where('product_type', $input_type);
                }
                if ($input_seller !== 'all') {
                    $query->where('seller_id', $input_seller);
                }
                if ($input_brand !== 'all') {
                    $query->where('brand_id', $input_brand);
                }

                $data = $query->get();
                $this->common->imageNameToUrl($data, 'portrait_img', $this->folder);
                foreach ($data as $value) {
                    $sellerImage = $value->seller;
                    $this->common->imageNameToUrl(array($sellerImage), 'image', $this->folder_seller);
                }
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $approved = __('label.approved');
                        $rejected = __('label.rejected');
                        $disapproved = __('label.disapproved');

                        $btn = '<div class="d-flex">';

                        $btn .= '<div class="mr-2 mb-2">';
                        $btn .= '<a class="approvedModal" style="cursor: pointer" title="' . $approved . '" data-toggle="modal" data-target="#ApprovedModal" data-id="' . $row->id . '" data-zone_id="' . $row->zone_id . '" data-compensation_id="' . $row->compensation_id . '" data-action="approved">';
                        $btn .= '<span class="badge badge-success">' . $approved . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        // Rejected button
                        $btn .= '<div>';
                        $btn .= '<a class="rejectedModal" style="cursor: pointer" title="' . $rejected . '" data-toggle="modal" data-target="#RejectedModal" data-id="' . $row->id . '">';
                        $btn .= '<span class="badge badge-danger">' . $disapproved . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $view = __('label.view');
                        $view_product = __('label.view_product');
                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('admin.request-item.edit', [$row->id]) . '" title="' . $view_product . '">';
                        $btn .= '<span class="order-badge order-badge-info mr-1">' . $view . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        return $btn;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }

            return view('admin.item_request.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {
            $params['data'] = Items::where('id', $id)->with('category', 'brand')->first();

            $params['TagName'] = $this->common->DetailsGetTagsName($params['data']['tag_id']);
            $params['Taxname'] = $this->common->DetailsGetTaxsName($params['data']['tax_id']);
            $this->common->imageNameToUrl(array($params['data']), 'portrait_img', $this->folder);

            $params['other_img'] = ItemsImages::where('item_id', $id)->get();
            $this->common->imageNameToUrl($params['other_img'], 'image', $this->folder);

            $params['p_attributes'] = ItemsAttributes::where('item_id', $id)->with('attributes')->get();

            foreach ($params['p_attributes'] as $key => $value) {
                $this->common->imageNameToUrl(array($value->attributes), 'icon', $this->folder_attributes);
                if ($value['field_type'] == 6) {
                    $this->common->imageNameToUrl(array($value), 'option', $this->folder);
                }
            }

            return view('admin.item_request.detail', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id, Request $request)
    {

        try {

            $data = Items::where('id', $id)->with('seller')->first();
            $Noti = $this->common->BasicNotiConfiguration(8);
            $seller = $data->seller;
            $status = $request->status;

            if ($status == 1) {
                $array = [
                    'status' => 1,
                    'is_approved' => 1,
                ];
                Items::where('id', $id)->update($array);


                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $this->common->Send_Mail(11, $seller->email);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Product Approved Successfully !";
                    $message = "The admin has reviewed and approved your product. It is now active in the store. Start receiving orders now!";

                    $this->common->SaveNotification('user', 4, $title, $message, $seller['id'], $id, 0, 0, "", "", "", $seller['device_token'], $seller['device_type']);
                }
            } else {
                $array = [
                    'status' => 0,

                ];
                Items::where('id', $id)->update($array);

                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $array = [
                        'product_name' => $data->name,
                    ];
                    $this->common->Send_Mail(12, $seller->email, $array);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $title = "Product Submission Disapproved !";
                    $message = "Your product" . " " . $data['name'] . " " . " has been disapproved. Please delete this product and resubmit it with all the required information";
                    $this->common->SaveNotification('user', 4, $title, $message, $seller['id'], $id, 0, 0, "", "", "", $seller['device_token'], $seller['device_type']);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
