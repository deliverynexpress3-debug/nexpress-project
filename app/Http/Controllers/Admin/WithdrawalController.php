<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\DeliveryWithdrawal;
use Illuminate\Http\Request;
use Exception;

class WithdrawalController extends Controller
{
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

                $input_status = $request['input_status'];
                $input_date = $request['input_date'];

                $query = DeliveryWithdrawal::with('seller', 'deliveryboy');

                if ($input_status != "all") {
                    $query->where('status', $input_status);
                }
                if ($input_date != null && isset($input_date)) {
                    $query->whereDate('created_at',  $input_date);
                }
                $data = $query->latest()->get();
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->addColumn('action', function ($row) {
                        // $optionsHtml = "<select onchange='change_status(\"$row->id\", this.value)' class='form-control'>";
                        // $optionsHtml .= "<option value='0'" . ($row->status == 0 ? ' selected' : '') . ">" . __('label.pending') . "</option>";
                        // $optionsHtml .= "<option value='1'" . ($row->status == 1 ? ' selected' : '') . ">" . __('label.approved') . "</option>";
                        // $optionsHtml .= "<option value='2'" . ($row->status == 2 ? ' selected' : '') . ">" . __('label.rejected') . "</option>";
                        // $optionsHtml .= "</select>";
                        // return $optionsHtml;
                    })
                    ->addColumn('status', function ($row) {
                        $processing = __('label.processing');
                        $paid = __('label.paid');
                        $rejected = __('label.rejected');

                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->status == 0) {
                            $btn .= '<span class="status-badge badge-warning mr-1">' . $processing . '</span>';
                        } else if ($row->status == 1) {
                            $btn .= '<span class="status-badge badge-success mr-1">' . $paid . '</span>';
                        } else if ($row->status == 2) {
                            $btn .= '<span class="status-badge badge-danger mr-1">' . $rejected . '</span>';
                        }
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('settlement_no', function ($row) {
                        return $this->common->GetSettelmentNo($row->settlement_ids);
                    })
                    ->rawColumns(['action', 'status', 'settlement_no'])
                    ->make(true);
            }
            return view('admin.withdrawal.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
