<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Customer;
use App\Models\Review;
use Illuminate\Http\Request;
use Exception;

class ReviewController extends Controller
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
            $seller = SellerData();

            $params['customer'] = Customer::where('is_seller', 0)->where('status', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_customer = $request['input_customer_id'];

                $query = Review::where('content_id', $seller['id'])->where('type', 2)->with('customer');

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
                    ->addColumn('action', function ($row) {
                        if ($row->status == 1) {
                            $showLabel = __('label.show');
                            return "<a onclick='review_change_status($row->id)' id='$row->id' style='cursor: pointer;'><span class='status-active'>$showLabel</span></a>";
                        } else {
                            $hideLabel = __('label.hide');
                            return "<a onclick='review_change_status($row->id)' id='$row->id' style='cursor: pointer;'><span class='status-inactive'>$hideLabel</span></a>";
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }

            return view('seller.review.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }

    public function show($id)
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
