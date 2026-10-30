<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Items;
use App\Models\Review;
use Illuminate\Http\Request;
use Exception;

class ReviewsController extends Controller
{
    public function index(Request $request) // Type 1-Restaurant, 2-Review
    {
        try {

            $params['data'] = [];
            $params['customer'] = Customer::where('is_seller', 0)->latest()->get();
            $params['seller'] = Customer::where('is_seller', 1)->where('status', 1)->latest()->get();
            $params['item'] = Items::where('status', 1)->latest()->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_customer_id = $request['input_customer_id'];
                $input_seller_id = $request['input_seller_id'];
                $input_item_id = $request['input_item_id'];
                $input_type = $request['input_type'];


                $query = Review::query();
                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('review', 'LIKE', "%{$input_search}%");
                    });
                }

                if ($input_type != "all") {
                    $query->where('type', $input_type);
                }
                if ($input_customer_id != "all") {
                    $query->where('customer_id', $input_customer_id);
                }
                if ($input_seller_id != "all") {
                    $query->where('content_id', $input_seller_id);
                }
                if ($input_item_id != "all") {
                    $query->where('content_id', $input_item_id);
                }

                $data = $query->with('customer', 'seller', 'item')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form onsubmit="return confirm(\'' . __('label.delete_review') . '\');" method="POST" action="' . route('admin.reviews.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;" title="' . __('label.delete') . '"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {

                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id)' class='show-btn'>" .  __('label.show') . "</button>";
                        } else {
                            return "<button type='button' id='$row->id' onclick='change_status($row->id)' class='hide-btn'>" .  __('label.hide') . "</button>";
                        }
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.reviews.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
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
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Review::where('id', $id)->first();
            if (isset($data)) {

                $data->delete();
            }
            return redirect()->route('admin.reviews.index')->with('success', __('label.review_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
