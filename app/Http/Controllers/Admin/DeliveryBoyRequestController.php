<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Compensation;
use App\Models\DeliveryBoy;
use App\Models\Zones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class DeliveryBoyRequestController extends Controller
{
    private $folder = "delivery_boy";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['zone'] = Zones::where('status', 1)->latest()->get();
            if ($request->ajax()) {

                $query = DeliveryBoy::where('verification_status', '!=', 1)->latest();
                $data = $query->get();
                $this->common->imageNameToUrl($data, 'image', $this->folder);

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $approvedLabel = __('label.approved');
                        $rejectedLabel = __('label.rejected');

                        // Approved → Open Modal
                        $btn  = "<button 
                                    type='button'
                                    class='show-btn mr-2 approvedModal'
                                    data-toggle='modal'
                                    data-target='#ApprovedModal'
                                    data-id='{$row->id}'
                                >
                                    {$approvedLabel}
                                </button>";

                        // Rejected → Direct
                        $btn .= "<button 
                                    type='button'
                                    class='hide-btn'
                                    onclick='change_status({$row->id}, 2)'
                                >
                                    {$rejectedLabel}
                                </button>";

                        return $btn;
                    })


                    // ->addColumn('action', function ($row) {

                    //     $approvedLabel = __('label.approved');
                    //     $rejectedLabel = __('label.rejected');
                    //     $btn = "<button type='button' class='show-btn mr-2' id='$row->id' onclick='change_status($row->id, 1)'>$approvedLabel</button>";
                    //     $btn .= "<button type='button' class='hide-btn' id='$row->id' onclick='change_status($row->id, 2)'>$rejectedLabel</button>";
                    //     return $btn;
                    // })
                    ->addColumn('email', function ($row) {
                        $Email = $this->common->MaskEmail();
                        return Demo_Mode() != 1 ? $Email($row->email) : $row->email;
                    })
                    ->addColumn('mobile_number', function ($row) {
                        $MobileNumber = $this->common->MaskMobileNumber();
                        return Demo_Mode() != 1 ? $MobileNumber($row->mobile_number) : $row->mobile_number;
                    })
                    ->addColumn('date', function ($row) {
                        $date =  $this->common->GetDateTime($row->created_at);
                        return $date;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('admin.become_deliveryboy.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show(Request $request)
    {
        try {
            if ($request->status == 1) {
                $validator = Validator::make($request->all(), [
                    'zone_id' => 'required',
                ]);
                if ($validator->fails()) {
                    $errs = $validator->errors()->all();
                    return response()->json(['status' => 400, 'errors' => $errs]);
                }
            }

            $id = $request['id'];
            $status = $request['status'];

            $deliveryBoy = DeliveryBoy::where('id', $id)->first();

            if ($status == 1) {

                $deliveryBoy->zone_id = implode(',', $request->zone_id);
                $deliveryBoy->verification_status = 1;
                $deliveryBoy->status = 1;
                $deliveryBoy->save();

                // Serviceable zones
                $zones = $this->common->GetServicableZone($deliveryBoy->zone_id);

                $zoneDetails = collect($zones)->map(fn($item) => "{$item['zone_name']} ({$item['zipcodes']})")->implode(', ');
                $serviceArea = $zoneDetails;
                $result = [];
                preg_match_all('/([^,(]+)\(([^)]+)\)/', $serviceArea, $matches, PREG_SET_ORDER);
                foreach ($matches as $match) {
                    $zoneName = trim($match[1]); // Fast Zone / Super Zone
                    $areas = array_map('trim', explode(',', $match[2])); // split zipcodes by comma
                    $result[$zoneName] = $areas;
                }

                $mailData = [
                    'status'       => 'approved',
                    'user_name'    => $deliveryBoy->user_name,
                    'service_area' => $result,
                ];
            } else {

                $deliveryBoy->verification_status = 0;
                $deliveryBoy->status = 0;
                $deliveryBoy->save();

                $mailData = [
                    'status'    => 'rejected',
                    'user_name' => $deliveryBoy->user_name,
                ];
            }

            $Noti = $this->common->BasicNotiConfiguration('delivery-boy-req-status');

            if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                $this->common->Send_Mail($Noti['type'], $deliveryBoy->email, $mailData);
            }
            if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                $this->common->SaveNotification('user', $Noti['type'], 0, 0, $deliveryBoy['id'], "", $deliveryBoy['device_token'], $deliveryBoy['device_type'], $mailData);
            }

            return response()->json(['status' => 200, 'success' => __('label.status_changed')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
