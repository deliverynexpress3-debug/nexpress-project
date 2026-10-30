<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Provider;
use App\Models\Withdrawal_Request;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        try {
            $seller = SellerData();
            $params['data'] = [];
            $params['data'] = Customer::where('id', $seller['id'])->first();

            if ($request->ajax()) {
                $data = Withdrawal_Request::where('type', 1)->where('type_id', $seller['id'])->orderBy('status', 'asc')->orderBy('id', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $pending = __('label.pending');
                        $approved = __('label.approved');
                        $rejected = __('label.rejected');

                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->status == 0) {
                            $btn .= '<span class="status-badge badge-danger mr-1">' . $pending . '</span>';
                        } else if ($row->status == 1) {
                            $btn .= '<span class="status-badge badge-success mr-1">' . $approved . '</span>';
                        } else if ($row->status == 2) {
                            $btn .= '<span class="status-badge badge-warning mr-1">' . $rejected . '</span>';
                        }
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->make(true);
            }

            return view('seller.wallet.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:100',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(array('status' => 400, 'errors' => $errs));
            }

            $seller = SellerData();
            $seller_id = $seller['id'];

            if ($request['amount'] <= $seller['wallet_amount']) {

                $insert = new Withdrawal_Request();
                $insert->type = 1;
                $insert->type_id = $seller_id;
                $insert->price = $request['amount'];
                $insert->payment_type = "";
                $insert->payment_detail = "";
                $insert->status = 0;

                if ($insert->save()) {

                    Customer::where('id', $seller_id)->decrement('wallet_amount', $request['amount']);
                    return response()->json(array('status' => 200, 'success' => __('label.controller.withdrawal_request_successfully')));
                } else {
                    return response()->json(array('status' => 400, 'errors' => __('label.data_not_save')));
                }
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.controller.insufficient_wallet_amount')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
}
