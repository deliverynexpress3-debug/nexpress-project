<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Common;
use App\Models\Items;
use App\Models\Offers;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class SliderController extends Controller
{

    private $folder_offers = "offers";
    private $folder_product = "product";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {
            $params['type'] = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['data'] = [];

            return view('admin.slider.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function GetTypeBased(Request $request)
    {
        try {

            $data = [];

            if ($request['content_type'] == 1) {

                $bannerlist = Banner::where('content_type', 1)->get();
                $bannerIds = [];
                for ($i = 0; $i < count($bannerlist); $i++) {
                    $bannerIds[] = $bannerlist[$i]['content_id'];
                }

                $data = Offers::whereNotIn('id', $bannerIds)->where('status', 1)->latest()->get();
            } else if ($request['content_type'] == 2) {

                $bannerlist = Banner::where('content_type', 2)->get();
                $bannerIds = [];
                for ($i = 0; $i < count($bannerlist); $i++) {
                    $bannerIds[] = $bannerlist[$i]['content_id'];
                }
                $data = Items::whereNotIn('id', $bannerIds)->where('status', 1)->latest()->get();
            }

            return response()->json(array('status' => 200, 'success' => __('label.data_add_successfully'), 'result' => $data));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function BannerList(Request $request)
    {
        try {

            if ($request['is_home_screen'] == 1) {
                $data = Banner::where('is_home_screen', $request['is_home_screen'])->orderBy('id', 'desc')->get();
                foreach ($data as $value) {
                    if ($value['content_type'] == 1) {
                        $offerImage = $value->offers;
                        $this->common->imageNameToUrl(array($offerImage), 'image', $this->folder_offers);
                    } else if ($value['content_type'] == 2) {
                        $itemImage = $value->item;
                        $this->common->imageNameToUrl(array($itemImage), 'portrait_img', $this->folder_product);
                    }
                }
            } else {
                $data = Banner::where('type_id', $request['top_type_id'])->where('is_home_screen', $request['is_home_screen'])->orderBy('id', 'desc')->get();
                foreach ($data as $value) {
                    if ($value['content_type'] == 1) {
                        $offerImage = $value->offers;
                        $this->common->imageNameToUrl(array($offerImage), 'image', $this->folder_offers);
                    } else if ($value['content_type'] == 2) {
                        $itemImage = $value->item;
                        $this->common->imageNameToUrl(array($itemImage), 'portrait_img', $this->folder_product);
                    }
                }
            }

            return response()->json(array('status' => 200, 'success' => __('label.data_add_successfully'), 'result' => $data));
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_home_screen' => 'required',
            'content_type' => 'required',
            'content_id' => 'required',
        ]);
        if ($validator->fails()) {
            $errs = $validator->errors()->all();
            return response()->json(array('status' => 400, 'errors' => $errs));
        }
        $banner = new Banner();
        $banner['is_home_screen'] = $request['is_home_screen'];
        $banner['type_id'] = $request['top_type_id'];
        $banner['content_type'] = $request['content_type'];
        $banner['content_id'] = $request['content_id'];

        if ($banner->save()) {
            return response()->json(array('status' => 200, 'success' => __('label.success_add_banner')));
        } else {
            return response()->json(array('status' => 400, 'errors' => __('label.error_add_banner')));
        }
    }
    public function destroy($id)
    {
        Banner::where('id', $id)->delete();
        return response()->json(array('status' => 200, 'success' => __('label.banner_delete')));
    }
}
