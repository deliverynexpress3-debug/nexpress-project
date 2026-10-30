<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\SellerRequest;
use Illuminate\Http\Request;
use Exception;

class BecomeSellerController extends Controller
{
    private $folder = "customer";
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

                $query = SellerRequest::where('status', 0)->with('customer')->latest();

                $data = $query->get();
                foreach ($data as $value) {
                    $customerImage = $value->customer;
                    $this->common->imageNameToUrl(array($customerImage), 'image', $this->folder);
                }

                $this->common->imageNameToUrl($data, 'store_logo', $this->folder, 'store');
                $this->common->imageNameToUrl($data, 'bank_proof', $this->folder, 'bank');

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $approved = __('label.approved');
                        $rejected = __('label.rejected');
                        $btn = '<div>';

                        $btn .= '<div class="mb-2">';
                        $btn .= '<a class="openModal" style="cursor: pointer" title="Approved" data-toggle="modal" data-target="#decisionModal" data-id="' . $row->id . '" data-action="approved">';
                        $btn .= '<span class="badge badge-success">' . $approved . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';

                        // Rejected button
                        $btn .= '<div>';
                        $btn .= '<a class="openModal" style="cursor: pointer" title="Rejected" data-toggle="modal" data-target="#decisionModal" data-id="' . $row->id . '" data-action="rejected">';
                        $btn .= '<span class="badge badge-danger">' . $rejected . '</span>';
                        $btn .= '</a>';
                        $btn .= '</div>';


                        return $btn;
                    })
                    ->addColumn('register_date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->make(true);
            }
            return view('admin.seller_request.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function show($id, Request $request)
    {
        try {
            $status = $request->status;

            $data = SellerRequest::where('id', $id)->with('customer')->first();
            $Noti = $this->common->BasicNotiConfiguration(5);

            if ($status == 1) {
                $array['status'] = 1;
                SellerRequest::where('id', $id)->update($array);
                $sellerRequest['is_seller'] = 1;
                Customer::where('id', $data['customer_id'])->update($sellerRequest);


                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    $this->common->Send_Mail(6, $data->customer->email);
                }
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    $seller = $data->customer;
                    $title = "Your Seller Request Has Been Approved!";
                    $message = "Congratulations! Your request to become an Seller has been approved. You can now start publishing your work and sharing it with readers. Log in to your account to get started!";
                    $this->common->SaveNotification('user', 2, $title, $message, $seller['id'], 0, 0, 0,  "", "", $seller['device_token'], $seller['device_type']);
                }
                return response()->json(array('status' => 200, 'success' => __('label.controller.request_approved')));
            } else {
                $seller = $data->customer;
                if ($Noti['status'] == 1 && $Noti['send_mail'] == 1) {
                    // Send Mail
                    $this->common->Send_Mail(7, $seller['email']);
                }

                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {
                    // Send Notification
                    $title = "Your Seller Request Has Been Rejected!";
                    $message = "We regret to inform you that your request to become an Seller on our platform has been rejected. If you have any questions or need further information, please feel free to contact our support team.";
                    $this->common->SaveNotification('user', 2, $title, $message, $seller['id'], 0, 0, 0,  "", "", $seller['device_token'], $seller['device_type']);
                }
                return response()->json(array('status' => 200, 'success' => __('label.controller.request_rejected')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
