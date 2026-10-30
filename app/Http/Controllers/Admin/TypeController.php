<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use Exception;


class TypeController extends Controller
{
    private $folder = "type";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['data'] = Type::orderby('sort_order', 'asc')->where('status', 1)->get();

            if ($request->ajax()) {

                $input_search = $request['input_search'];

                $query = Type::query();

                if ($input_search != null) {
                    $query = Type::where('name', 'LIKE', "%{$input_search}%");
                }

                $data = $query->orderby('sort_order', 'asc')->get();

                $this->common->imageNameToUrl($data, 'icon', $this->folder);
                $this->common->imageNameToUrl($data, 'banner_image', $this->folder);
                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $type_delete = __('label.delete_type');

                        $delete = '<form onsubmit="return confirm(\'' . $type_delete . '\');" method="POST"  action="' . route('admin.type.destroy', [$row->id]) . '">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="edit-delete-btn" style="outline: none;"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn edit_type" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-type="' . $row->type . '" data-icon="' . $row->icon . '" data-banner_image="' . $row->banner_image . '" data-bg_color="' . $row->bg_color . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
                        return $btn;
                    })
                    ->addColumn('status', function ($row) {
                        $activeLabel = __('label.show');
                        $inactiveLabel = __('label.hide');

                        $statusBtn = '';

                        if ($row->status == 1) {
                            $statusBtn = "<button type='button' id='{$row->id}' onclick='change_status({$row->id})' class='show-btn'>{$activeLabel}</button>";
                        } else {
                            $statusBtn = "<button type='button' id='{$row->id}' onclick='change_status({$row->id})' class='hide-btn'>{$inactiveLabel}</button>";
                        }

                        return $statusBtn;
                    })

                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.type.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'bg_color' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                'banner_image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($requestData['icon']) && $requestData['icon'] != null) {
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'type_');
            } else {
                $requestData['icon'] = "";
            }


            if (isset($requestData['banner_image']) && $requestData['banner_image'] != null) {
                $files1 = $requestData['banner_image'];
                $requestData['banner_image'] = $this->common->saveImage($files1, $this->folder, 'banner_');
            } else {
                $requestData['banner_image'] = "";
            }

            $requestData['sort_order'] = 0;
            $requestData['status'] = 1;

            $type_data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($type_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_type')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_add_type')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'bg_color' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg|max:5120',
                'banner_image' => 'image|mimes:jpeg,png,jpg|max:5120',
            ]);
            if ($validator->fails()) {
                $errs = $validator->errors()->all();
                return response()->json(['status' => 400, 'errors' => $errs]);
            }

            $requestData = $request->all();

            if (isset($requestData['icon'])) {
                $files = $requestData['icon'];
                $requestData['icon'] = $this->common->saveImage($files, $this->folder, 'type_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_icon']));
            }
            unset($requestData['old_icon']);

            if (isset($requestData['banner_image'])) {
                $files = $requestData['banner_image'];
                $requestData['banner_image'] = $this->common->saveImage($files, $this->folder, 'banner_');
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_banner_image']));
            }
            unset($requestData['old_banner_image']);

            $type_data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($type_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_type')]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.error_edit_type')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $data = Type::where('id', $id)->first();

            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['icon']);
                $this->common->deleteImageToFolder($this->folder, $data['banner_image']);
                $data->delete();
            }
            return redirect()->route('admin.type.index')->with('success', __('label.type_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Type::where('id', $id)->first();
            if (isset($data)) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();

                $updated_list = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();

                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status, 'active_types' => $updated_list]);
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    // Sortable
    public function TypeSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Type::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }

            return response()->json(['status' => 200, 'success' => __('label.success_sort_order')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
