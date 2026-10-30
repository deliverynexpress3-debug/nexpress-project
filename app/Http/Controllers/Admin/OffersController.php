<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Items;
use App\Models\Offers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class OffersController extends Controller
{
    private $folder = "offers";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $this->common->ActiveExpriedOffers();

            $params['data'] = [];
            $params['item'] = Items::where('status', 1)->where('is_approved', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_item = $request['input_item_id'];

                $query = Offers::with('item')->latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('title', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_item !== 'all') {
                    $query->where('item_id', $input_item);
                }

                $data = $query->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);
                $this->common->imageNameToUrl($data, 'banner_image', $this->folder);
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $offers_delete = __('label.delete_offers');

                        $delete = ' <form onsubmit="return confirm(\'' . $offers_delete . '\');" method="POST"  action="' . route('admin.offers.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_offers" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-title="' . $row->title . '" data-item_id="' . $row->item_id . '" data-type="' . $row->type . '" data-discount_amount="' . $row->discount_amount . '" data-valid_from="' . $row->valid_from . '" data-valid_until="' . $row->valid_until . '" data-description="' . $row->description . '" data-image="' . $row->image . '" data-banner_image="' . $row->banner_image . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $optionsHtml = "<select onchange='change_status(\"$row->id\", this.value)' class='form-control'>";
                        $optionsHtml .= "<option value='0'" . ($row->status == 0 ? ' selected' : '') . ">" . __('label.inactive') . "</option>";
                        $optionsHtml .= "<option value='1'" . ($row->status == 1 ? ' selected' : '') . ">" . __('label.active') . "</option>";
                        $optionsHtml .= "<option value='2'" . ($row->status == 2 ? ' selected' : '') . ">" . __('label.expired') . "</option>";
                        $optionsHtml .= "</select>";
                        return $optionsHtml;
                    })

                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.offers.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|min:2',
                'item_id' => 'required',
                'discount_amount' => 'required|numeric|min:1',
                'valid_from' => 'required',
                'valid_until' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
                'banner_image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($requestData['image']) && $requestData['image'] != null) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'offers_banner_');
            } else {
                $requestData['image'] = "";
            }

            if (isset($requestData['banner_image']) && $requestData['banner_image'] != null) {
                $files1 = $requestData['banner_image'];
                $requestData['banner_image'] = $this->common->saveImage($files1, $this->folder, 'offers_');
            } else {
                $requestData['banner_image'] = "";
            }
            $requestData['description'] = $request['description'] ?? "";
            $requestData['status'] = 1;
            $offer_data = Offers::updateOrCreate(['id' => $requestData['id']], $requestData);

            if (isset($offer_data->id)) {

                $NotiStatus = $this->common->BasicNotiConfiguration('new-special-offers');
                if ($NotiStatus['status'] == 1 && $NotiStatus['send_notification'] == 1) {

                    $array = [
                        'title' => $offer_data->title,
                        'imageURL' => $this->common->getImage($this->folder, $offer_data->image),
                        'type' => 'customer',
                    ];
                    $this->common->SaveNotification('all', $NotiStatus['type'], 0, 0, 0, $offer_data->id, "", "", $array);
                }

                return response()->json(array('status' => 200, 'success' => __('label.success_add_offer')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_offer')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|min:2',
                'item_id' => 'required',
                'discount_amount' => 'required|numeric|min:1',
                'valid_from' => 'required',
                'valid_until' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
                'banner_image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }
            $requestData = $request->all();

            if (isset($requestData['image']) && $requestData['image'] != null) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'offers_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            unset($requestData['old_image']);

            if (isset($requestData['banner_image']) && $requestData['banner_image'] != null) {
                $files1 = $requestData['banner_image'];
                $requestData['banner_image'] = $this->common->saveImage($files1, $this->folder, 'offers_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_banner_image']));
            }
            unset($requestData['old_banner_image']);
            $requestData['description'] = $request['description'] ?? "";

            $offer_data = Offers::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($offer_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_offer')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_offer')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Offers::where('id', $id)->first();

            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $this->common->deleteImageToFolder($this->folder, $data['banner_image']);
                $data->delete();
            }
            return redirect()->route('admin.offers.index')->with('success',  __('label.offers_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id, Request $request)
    {
        try {

            $data = Offers::where('id', $id)->first();
            if (isset($data)) {

                $data->update(['status' => $request['status']]);

                return response()->json(['status' => 200, 'success' => __('label.status_changed')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
