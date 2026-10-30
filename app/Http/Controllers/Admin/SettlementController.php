<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Settelment;
use Illuminate\Http\Request;
use Exception;

class SettlementController extends Controller
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

                $query = Settelment::with('seller', 'deliveryboy');

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
                    ->addColumn('status', function ($row) {
                        $pending = __('label.pending');
                        $settled = __('label.settled');

                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->status == 1) {
                            $btn .= '<span class="status-badge badge-warning mr-1">' . $pending . '</span>';
                        } else if ($row->status == 2) {
                            $btn .= '<span class="status-badge badge-success mr-1">' . $settled . '</span>';
                        }
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.settlement.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
