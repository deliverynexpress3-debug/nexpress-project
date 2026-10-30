<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Promocode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

class PromocodeController extends Controller
{
    private $folder = "promocode";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $this->common->ActiveExpriedPromocodes();

            $params['data'] = [];

            if ($request->ajax()) {
                $input_search = $request['input_search'];

                $query = Promocode::latest();

                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('name', 'LIKE', "%{$input_search}%");
                    });
                }
                $data = $query->latest()->get();

                $this->common->imageNameToUrl($data, 'image', $this->folder);
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $promocode_delete = __('label.delete_promocode');

                        $delete = ' <form onsubmit="return confirm(\'' . $promocode_delete . '\');" method="POST"  action="' . route('admin.promocode.destroy', [$row->id]) . '">
                        <input type="hidden" name="_token" value="' . csrf_token() . '">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_promocode" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-promo_code="' . $row->promo_code . '" data-name="' . $row->name . '" data-type="' . $row->type . '" data-discount_amount="' . $row->discount_amount . '" data-valid_from="' . $row->valid_from . '" data-valid_until="' . $row->valid_until . '" data-usage_limit="' . $row->usage_limit . '" data-user_limit_per_user="' . $row->user_limit_per_user . '" data-image="' . $row->image . '">';
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
            return view('admin.promocode.index', $params);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'discount_amount' => 'required|numeric|min:1',
                'valid_from' => 'required',
                'valid_until' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ];
            if ($request->usage_limit == 1) {
                $rules['user_limit_per_user'] = 'required|numeric|integer|min:1';
            }


            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();
            $requestData['seller_id'] = 0;
            $requestData['promo_code'] = Str::random(6);
            if ($requestData['usage_limit'] != 1) {
                $requestData['user_limit_per_user'] = 0;
            }

            if (isset($requestData['image']) && $requestData['image'] != null) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'promocode_');
            } else {
                $requestData['image'] = "";
            }
            $requestData['status'] = 1;
            $promocode_data = Promocode::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($promocode_data->id)) {

                $Noti = $this->common->BasicNotiConfiguration(13);
                if ($Noti['status'] == 1 && $Noti['send_notification'] == 1) {

                    $array = [
                        'title' => $promocode_data['title'],
                        'description' => "New promo code just dropped! Apply it at checkout and enjoy instant savings.",
                        'imageURL' => $this->common->getImage($this->folder, $promocode_data['image']),
                    ];

                    $this->common->sendnotification($array);
                }

                return response()->json(array('status' => 200, 'success' => __('label.success_add_promocode')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_add_promocode')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function update(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'discount_amount' => 'required|numeric|min:1',
                'valid_from' => 'required',
                'valid_until' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ];
            if ($request->usage_limit == 1) {
                $rules['user_limit_per_user'] = 'required|numeric|integer|min:1';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if ($requestData['usage_limit'] != 1) {
                $requestData['user_limit_per_user'] = 0;
            }

            if (isset($requestData['image']) && $requestData['image'] != null) {
                $files = $requestData['image'];
                $requestData['image'] = $this->common->saveImage($files, $this->folder, 'promocode_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']));
            }
            unset($requestData['old_image']);

            $promocode_data = Promocode::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($promocode_data->id)) {
                return response()->json(array('status' => 200, 'success' => __('label.success_edit_promocode')));
            } else {
                return response()->json(array('status' => 400, 'errors' => __('label.error_edit_promocode')));
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function destroy($id)
    {
        try {
            $data = Promocode::where('id', $id)->first();

            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image']);
                $data->delete();
            }
            return redirect()->route('admin.promocode.index')->with('success',  __('label.promocode_delete'));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function show($id, Request $request)
    {
        try {

            $data = Promocode::where('id', $id)->first();
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
