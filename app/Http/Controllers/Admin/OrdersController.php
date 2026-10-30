<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryBoy;
use App\Models\Items;
use App\Models\Orders;
use App\Models\OrdersItems;
use App\Models\OrdersTracking;
use App\Models\PickupItems;
use App\Models\SellerRequest;
use App\Models\Vehicle;
use App\Models\ZipCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrdersController extends Controller
{
    private $folder = "product";
    private $folder_customer = "customer";
    private $folder_deliveryboy = "delivery_boy";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['deliveryboy'] = DeliveryBoy::where('status', 1)->where('verification_status', 1)->latest()->get();
            $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();
            $params['item'] = Items::where('status', 1)->latest()->get();
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();;

            $params['PendingOrders'] = Orders::where('order_status', 1)->count();
            $params['ConfirmedOrders'] = Orders::where('order_status', 2)->count();
            $params['PickingOrders'] = Orders::where('order_status', 3)->count();
            $params['PackedOrders'] = Orders::where('order_status', 4)->count();
            $params['OutforOrders'] = Orders::where('order_status', 5)->count();
            $params['DeliverdOrders'] = Orders::where('order_status', 6)->count();
            $params['CancelledOrders'] = Orders::where('order_status', 7)->count();
            $params['RejectedOrders'] = Orders::where('order_status', 8)->count();

            if ($request->ajax()) {
                $input_req_status = $request['input_req_status'];
                $input_search = $request['input_search'];
                $input_payment_method = $request['input_payment_method'];
                $input_seller = $request['input_seller_id'];
                $input_customer = $request['input_customer_id'];
                $input_type = $request['input_type'];

                if ($input_req_status == 0) {
                    $query = Orders::with('customer', 'customer_address', 'seller');
                } else {
                    $query = Orders::where('order_status', $input_req_status)->with('customer', 'customer_address', 'seller');
                }

                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('group_order_id', 'LIKE', "%{$input_search}%")->orWhere('order_notes', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_payment_method !== 'all') {
                    $query->where('payment_method', $input_payment_method);
                }

                if ($input_seller !== 'all') {
                    $query->where('seller_id', $input_seller);
                }
                if ($input_customer !== 'all') {
                    $query->where('customer_id', $input_customer);
                }
                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->orderBy('id', 'desc')->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $btn = '<div class="d-flex justify-content-around">';

                        if ($row->order_status == 1) {

                            // Accept Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-order_id="' . $row->group_order_id . '"data-status="2"><i class="fa-solid fa-check fa-xl"></i>
                                    </a>';

                            // Reject Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-order_id="' . $row->group_order_id . '"data-status="8"><i class="fa-solid fa-xmark fa-xl"></i>
                                    </a>';
                        } else if ($row->order_status == 2) {
                            // preparing Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-order_id="' . $row->group_order_id . '"data-status="3"><i class="fa-solid fa-box-open fa-xl"></i>
                             </a>';
                        } else if ($row->order_status == 3) {
                            // Reay-for-pickup Button
                            $btn .= '<a class="edit-delete-btn order-status-btn" data-toggle="modal" href="#orderActionModal"data-order_id="' . $row->group_order_id . '"data-status="4"><i class="fa-solid fa-box fa-xl"></i>
                             </a>';
                        } else if ($row->order_status == 4) {
                            // Assign Delivery Boy Button
                            $btn .= '<a class="edit-delete-btn delivery_assign" data-toggle="modal" href="#EditModel" data-order_id="' . $row->group_order_id . '" data-id="' . $row->id . '" title="' . __('label.delivery_assignment') . '"><i class="fa-solid fa-truck fa-xl"></i></a>';
                        }

                        // ✅ View Button (All Status)
                        $btn .= '<a href="' . route('admin.order.edit', [$row->group_order_id]) . '" 
                                    class="edit-delete-btn">';
                        $btn .= '<i class="fa-solid fa-eye fa-xl"></i>';
                        $btn .= '</a>';

                        $btn .= '</div>';

                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $pending = __('label.pending');
                        $confirmed = __('label.confirmed');
                        $picking = __('label.picking');
                        $packed = __('label.packed');
                        $out_for_delivery = __('label.out_for_delivery');
                        $delivered = __('label.delivered');
                        $cancelled = __('label.cancelled');
                        $rejected = __('label.rejected');

                        $btn = '<div class="d-flex justify-content-around">';

                        switch ($row->order_status) {

                            case 1:
                                $btn .= '<span class="status-badge badge-warning mr-1">' . $pending . '</span>';
                                break;

                            case 2:
                                $btn .= '<span class="status-badge badge-primary mr-1">' . $confirmed . '</span>';
                                break;

                            case 3:
                                $btn .= '<span class="status-badge badge-info mr-1">' . $picking . '</span>';
                                break;

                            case 4:

                                $btn .= '<span class="status-badge badge-secondary mb-1 d-block">'
                                    . $packed .
                                    '</span>';

                                if (isset($row->is_assigned)) {
                                    $yes = __('label.yes');
                                    $no = __('label.no');
                                    $is_assigned = __('label.is_assigned');

                                    $assignedBadge = $row->is_assigned == 1
                                        ? '<span class="text-success font-weight-bold">' . $yes . '</span>'
                                        : '<span class="text-danger font-weight-bold">' . $no . '</span>';

                                    $btn .= '<div style="font-size:14px; font-weight:600;">
                                                    ' . $is_assigned . ' : ' . $assignedBadge . '
                                                 </div>';
                                }

                                break;

                            case 5:
                                $btn .= '<span class="status-badge badge-dark mr-1">' . $out_for_delivery . '</span>';
                                break;

                            case 6:
                                $btn .= '<span class="status-badge badge-success mr-1">' . $delivered . '</span>';
                                break;

                            case 7:
                                $btn .= '<span class="status-badge badge-danger mr-1">' . $cancelled . '</span>';
                                break;

                            case 8:
                                $btn .= '<span class="status-badge badge-danger mr-1">' . $rejected . '</span>';
                                break;

                            default:
                                $btn .= '<span class="status-badge badge-light mr-1">N/A</span>';
                                break;
                        }

                        $btn .= '</div>';

                        return $btn;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }

            return view('admin.orders.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($group_order_id)
    {
        try {
            $params['group_order_no'] = $group_order_id;

            $params['orders'] = Orders::where('group_order_id', $group_order_id)->with('customer', 'seller')->first();
            if (!$params['orders']) {
                return redirect()->route('admin.order.index');
            }
            $params['orders']->items = OrdersItems::where('group_order_id', $group_order_id)->with('item')->get();

            foreach ($params['orders']['items'] as $key => $value) {
                $itemImage = $value->item;
                $value['item_image'] = $itemImage ? $this->common->getImage($this->folder, $itemImage['portrait_img']) : '';
                $value['item_name'] = $itemImage?->name ?? '';
                $value['per_unit'] = $itemImage ? ($itemImage->per_unit . $itemImage->unit) : '';
            }

            $params['order_date'] =  $this->common->GetDateTime($params['orders']['updated_at']);
            $params['order_tracking'] = OrdersTracking::where('group_order_id', $group_order_id)->get();

            $customerImage = $params['orders']->customer;
            if ($customerImage) {
                $this->common->imageNameToUrl(array($customerImage), 'image', $this->folder_customer);
            }

            $sellerImage = $params['orders']->seller;
            if ($sellerImage) {
                $this->common->imageNameToUrl(array($sellerImage), 'image', $this->folder_customer);
            }

            $params['assign_orders'] = DeliveryAssignment::where('group_order_id', $group_order_id)->with('deliveryboy')->first();
            if ($params['assign_orders'] && $params['assign_orders']->deliveryboy) {
                $this->common->imageNameToUrl(array($params['assign_orders']->deliveryboy), 'image', $this->folder_deliveryboy);
            }

            $params['delivery_instructor'] = $this->common->GetDeliveryInstructorByIds($params['orders']['delivery_instructor_id']);

            return view('admin.orders.edit', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ViewOrder($group_order_id)
    {

        try {
            $orders = Orders::where('group_order_id', $group_order_id)->first();
            $assignOrder = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
            $seller = SellerRequest::where('customer_id', $orders['seller_id'])->first();
            $items = OrdersItems::where('group_order_id', $group_order_id)
                ->with('item')
                ->get()
                ->map(function ($row) {
                    return [
                        'order_number' => $row->order_number,
                        'item_name'    => optional($row->item)->name ?? '',
                        'qty'          => $row->order_quantity,
                        'price'        => $row->item_price,
                    ];
                });

            return response()->json([
                'group_order_id'       => $group_order_id,
                'total_payable_amount' => $orders->total_payable_amount,
                'pickup_location'   => $seller->store_address ?? '',
                'pickup_time'          => $assignOrder->pickup_time ?? null,
                'estimated_time'       => $assignOrder->estimated_delivery_time ?? null,
                'delivery_boy_id'      => $assignOrder->delivery_boy_id ?? null,
                'items'                => $items
            ]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function OrderChangeStatus($group_order_id, Request $request) //Status-2-Accepted ,3-Preparing ,4-Ready-for-pickup ,8-Rejected
    {
        try {
            $status = $request['req_status'];
            $order = Orders::where('group_order_id', $group_order_id)->with('seller', 'customer')->first();

            $order->order_status = $status;
            $order->save();

            $customer = $order->customer;
            $seller = $order->seller;

            $array = [
                'status_type' => 'seller-side',
                'status' => $status,
                'group_order_id' => $group_order_id,
                'seller_name' => $seller['user_name'],
                'customer_name' => $customer['user_name'],
            ];
            $Noti = $this->common->BasicNotiConfiguration('order-status-change');

            $array['notification_type'] = $Noti['type'];
            $this->common->OrderTrackingAdd($order->group_order_id, $status);
            if ($Noti['status'] == 1) {
                if ($Noti['send_mail'] == 1) {
                    $this->common->Send_Mail($Noti['type'], $customer['email'], $array);
                }
                if ($Noti['send_notification'] == 1) {
                    $this->common->SaveNotification('customer', $Noti['type'], $customer['id'], 0, 0, $group_order_id, $customer['device_token'], $customer['device_type'], $array);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.status_changed')));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function OrderAssignment($group_order_id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'delivery_boy_id'   => 'required',
                'pickup_time'        => 'required',
                'estimated_delivery_time'        => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors()->all(),
                ]);
            }
            $order = Orders::where('group_order_id', $group_order_id)->with('customer', 'customer_address')->first();
            DeliveryAssignment::updateOrCreate(
                ['group_order_id' => $request->group_order_id ?? $group_order_id],
                [
                    'delivery_boy_id' => $request->delivery_boy_id,
                    'pickup_location'   => $request->pickup_location,
                    'pickup_time'     => $request->pickup_time,
                    'estimated_delivery_time'     => $request->estimated_delivery_time,
                    'earning'                   => $order->delivery_boy_commission,
                    'status'         => 1,
                ]
            );

            Orders::where('group_order_id', $group_order_id)->update(['is_assigned' => 1]);

            $assign = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
            $boy = DeliveryBoy::where('id', $assign['delivery_boy_id'])->first();
            $deliveryAdd = $order['customer_address'];
            $delivery_address = collect([
                $deliveryAdd['address'] ?? null,
                $deliveryAdd['area'] ?? null,
                $deliveryAdd['city'] ?? null,
                $deliveryAdd['state'] ?? null,
                $deliveryAdd['country'] ?? null,
            ])->filter()->implode(', ');

            $delivery_address .= isset($deliveryAdd['pincode'])
                ? ' - ' . $deliveryAdd['pincode']
                : '';
            $array = [
                'deliveryboy_name' => $boy['full_name'],
                'customer_name' => $order->customer->user_name ?? '',
                'delivery_address' => $delivery_address,
                'total_payable_amount' => $order['total_payable_amount'],
                'group_order_id' => $order['group_order_id'],
                'pickup_time' => $assign['pickup_time'],
                'pickup_location' => $assign['pickup_location'],
                'estimated_delivery_time' => $assign['estimated_delivery_time'],
            ];
            $Noti = $this->common->BasicNotiConfiguration('order-assignment');

            if ($Noti['status'] == 1) {
                if ($Noti['send_mail'] == 1) {
                    $this->common->Send_Mail($Noti['type'], $boy['email'], $array);
                }
                if ($Noti['send_notification'] == 1) {
                    $this->common->SaveNotification('deliveryboy', $Noti['type'], 0, 0, $boy['id'], $group_order_id, $boy['device_token'], $boy['device_type'], $array);
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.orders_assigned')));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function DeliveryBoyList(Request $request)
    {
        $group_order_id = $request->order_id;

        $orders = Orders::with([
            'customer_address',
            'seller',
            'item'
        ])->where('group_order_id', $group_order_id)->get();

        if ($orders->count()) {

            /* ================= CUSTOMER ZIPCODES ================= */
            $customerZipcodes = $orders->pluck('customer_address.pincode')->filter();

            /* ================= DELIVERY BOYS ================= */
            $deliveryBoys = DeliveryBoy::where('verification_status', 1)
                ->where('status', 1)
                ->latest()
                ->get();

            $exactMatchedDeliveryBoy = collect();
            $closestMatchedDeliveryBoy = collect();
            $closestDistance = null;

            /* ================= VEHICLES ================= */
            $vehicles = Vehicle::where('is_used', 0)
                ->where('status', 1)
                ->latest()
                ->get();

            $assignedVehicle = null;

            /* ================= ASSIGNED DELIVERY ================= */
            $assignedOrder = DeliveryAssignment::where('group_order_id', $group_order_id)
                ->whereNotNull('delivery_boy_id')
                ->first();

            $assignedDeliveryBoy = null;

            if ($assignedOrder) {

                $assignedDeliveryBoy = DeliveryBoy::find($assignedOrder->delivery_boy_id);
                if ($assignedDeliveryBoy && !$deliveryBoys->contains('id', $assignedDeliveryBoy->id)) {
                    $deliveryBoys->push($assignedDeliveryBoy);
                }

                if ($assignedOrder->vehicle_id) {
                    $assignedVehicle = Vehicle::find($assignedOrder->vehicle_id);
                    if ($assignedVehicle && !$vehicles->contains('id', $assignedVehicle->id)) {
                        $vehicles->push($assignedVehicle);
                    }
                }
            }

            /* ================= ZONE MATCHING ================= */
            foreach ($deliveryBoys as $deliveryBoy) {

                $explodedZoneIds = explode(",", $deliveryBoy->zone_id);

                foreach ($explodedZoneIds as $zoneId) {

                    $zipcodeRecord = ZipCode::where('id', $zoneId)->first();

                    if ($zipcodeRecord) {
                        foreach ($customerZipcodes as $custZip) {

                            if ($zipcodeRecord->zipcode == $custZip) {
                                if (!$exactMatchedDeliveryBoy->contains('id', $deliveryBoy->id)) {
                                    $exactMatchedDeliveryBoy->push($deliveryBoy);
                                }
                                continue 3;
                            }

                            $distance = abs($zipcodeRecord->zipcode - $custZip);

                            if (is_null($closestDistance) || $distance < $closestDistance) {
                                $closestDistance = $distance;
                                $closestMatchedDeliveryBoy = collect([$deliveryBoy]);
                            } elseif ($distance == $closestDistance && !$closestMatchedDeliveryBoy->contains('id', $deliveryBoy->id)) {
                                $closestMatchedDeliveryBoy->push($deliveryBoy);
                            }
                        }
                    }
                }
            }

            $finalDeliveryBoyResult = collect();

            if ($exactMatchedDeliveryBoy->isNotEmpty()) {
                $finalDeliveryBoyResult = $exactMatchedDeliveryBoy;
            } elseif ($closestMatchedDeliveryBoy->isNotEmpty()) {
                $finalDeliveryBoyResult = $closestMatchedDeliveryBoy;
            } else {
                $finalDeliveryBoyResult = $deliveryBoys;
            }

            if ($assignedDeliveryBoy && !$finalDeliveryBoyResult->contains('id', $assignedDeliveryBoy->id)) {
                $finalDeliveryBoyResult->push($assignedDeliveryBoy);
            }

            if ($assignedVehicle && !$vehicles->contains('id', $assignedVehicle->id)) {
                $vehicles->push($assignedVehicle);
            }

            /* ================= ORDER ITEMS DETAILS ================= */
            $items = [];

            // Preload seller requests (performance++)
            $sellerRequests = SellerRequest::whereIn(
                'customer_id',
                $orders->pluck('seller_id')->unique()
            )->get()->keyBy('customer_id');

            foreach ($orders as $order) {

                $sellerId = $order->seller_id;

                $sellerRequest = $sellerRequests[$sellerId] ?? null;

                if (!isset($items[$sellerId])) {

                    $items[$sellerId] = [
                        'seller_id'      => $sellerId,
                        'seller_name'    => $sellerRequest?->customer?->name ?? '-',
                        'pickup_time'    => $sellerRequest?->pickup_time ?? '-',
                        'pickup_address' => $sellerRequest?->store_address ?? '-',
                        'items'          => []
                    ];
                }

                $items[$sellerId]['items'][] = [
                    'order_number' => $order->order_number ?? '',
                    'item_id'  => $order->item?->id ?? '-',
                    'item_title'  => $order->item?->name ?? '-',
                    'quantity'    => $order->order_quantity,
                    'total_price' => $order->item_amount
                ];
            }

            // Reset keys
            $items = array_values($items);
            /* ================= FINAL RESPONSE ================= */
            return response()->json([
                'status' => 200,
                'success' => __('label.data_get_succesfully'),
                'result' => $finalDeliveryBoyResult->unique('id')->values(),
                'assigned_delivery_boy_id' => $assignedDeliveryBoy?->id,
                'vehicles' => $vehicles->unique('id')->values(),
                'assigned_vehicle_id' => $assignedVehicle?->id,
                'items' => $items
            ]);
        }

        return response()->json([
            'status' => 404,
            'success' => __('label.no_data_found')
        ]);
    }
    public function DownloadInvoice($group_order_id)
    {

        $params['orders'] = Orders::where('group_order_id', $group_order_id)->with('customer_address', 'customer')->first();
        if (!$params['orders']) {
            return response()->json(['status' => 400, 'errors' => __('api_msg.data_not_found')]);
        }
        $params['order_date'] = $this->common->GetDateTime($params['orders']['created_at']);

        $params['orders']->items = OrdersItems::where('group_order_id', $group_order_id)->with('item')->get();
        foreach ($params['orders']->items as $key => $value) {
            $item = $value->item;
            $value['item_name'] = $item?->name ?? '';
            $value['item_image'] = $item ? $this->common->getImage($this->folder, $item['portrait_img']) : '';
            $value['item_per_unit'] = $item ? ($item->per_unit . $item->unit) : '';
        }
        $params['delivery_instructor'] = $this->common->GetDeliveryInstructorByIds($params['orders']['delivery_instructor_id']);

        $pdfName = config('app.name') . "-Invoice_-" . $group_order_id . ".pdf";

        // Load and store PDF
        $pdf = Pdf::loadView('invoice', ['result' => $params])->setPaper([0, 0, 700, 890]);
        return $pdf->stream($pdfName);
    }
}
