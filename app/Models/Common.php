<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Exception;

class Common extends Model
{

    private $folder_product = "product";
    private $folder_category = "category";
    private $folder_item = "product";
    private $folder_attributes = "attributes";
    private $folder_customer = "customer";
    private $folder_delivery_instructor = "delivery_instructor";
    private $folder_delivery_boy = "delivery_boy";

    // Image Functions
    public function saveImage($org_name, $folder, $prefix = "")
    {
        try {
            $img_ext = $org_name->getClientOriginalExtension();
            $filename = $prefix . date('d_m_Y_') . rand(1111, 9999) . '.' . $img_ext;
            $org_name->move(base_path('storage/app/public/' . $folder), $filename);

            return $filename;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function getImage($folder = "", $name = "", $type = "")
    {
        try {
            $appName = Config::get('app.image_url');

            if ($folder != "" && $name != "") {

                if (Storage::disk('public')->exists($folder . '/' . $name)) {
                    $name = $appName . $folder . '/' . $name;
                } else {
                    if ($folder == "customer" || $folder == "delivery_boy") {
                        $name = asset('assets/imgs/default.png');
                    } else {
                        $name = asset('assets/imgs/no_img.png');
                    }
                }
            } else {
                if ($folder == "customer" || $folder == "delivery_boy") {
                    $name = asset('assets/imgs/default.png');
                } else {
                    $name = asset('assets/imgs/no_img.png');
                }
            }
            return $name;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function imageNameToUrl($array, $column, $folder, $type = "")
    {

        try {

            $appName = Config::get('app.image_url');

            foreach ($array as $value) {

                if (isset($value[$column]) && $value[$column] != "") {


                    if ($folder == "customer" || $folder == "delivery_boy") {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            if ($type == 'store' || $type == 'bank' || $type == 'document') {
                                $value[$column] = asset('assets/imgs/no_img.png');
                            } else {
                                $value[$column] = asset('assets/imgs/default.png');
                            }
                        }
                    } else {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            $value[$column] = asset('assets/imgs/no_img.png');
                        }
                    }
                } else {

                    if ($folder == "customer" || $folder == "delivery_boy") {

                        if ($type == 'store' || $type == 'bank' || $type == 'document') {
                            $value[$column] = asset('assets/imgs/no_img.png');
                        } else {
                            $value[$column] = asset('assets/imgs/default.png');
                        }
                    } else {
                        $value[$column] = asset('assets/imgs/no_img.png');
                    }
                }
            }

            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function deleteImageToFolder($folder, $name)
    {
        try {

            Storage::disk('public')->delete($folder . '/' . $name);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function generateLetterAvatar($text, $folder, $prefix = "")
    {
        $letter = strtoupper(substr($text, 0, 1));

        // Unique color from text (email/username)
        $hash = md5($text);
        $r = hexdec(substr($hash, 0, 2));
        $g = hexdec(substr($hash, 2, 2));
        $b = hexdec(substr($hash, 4, 2));

        $size = 200;
        $img = imagecreatetruecolor($size, $size);

        // Dynamic background color
        $bgColor = imagecolorallocate($img, $r, $g, $b);
        imagefilledrectangle($img, 0, 0, $size, $size, $bgColor);

        // White text
        $textColor = imagecolorallocate($img, 255, 255, 255);
        $fontPath = public_path('assets/fonts/Poppins-SemiBold.ttf');
        $fontSize = 80;

        // Center calculation
        $bbox = imagettfbbox($fontSize, 0, $fontPath, $letter);
        $textWidth = $bbox[2] - $bbox[0];
        $textHeight = $bbox[1] - $bbox[7];

        $x = ($size - $textWidth) / 2;
        $y = ($size + $textHeight) / 2;

        imagettftext($img, $fontSize, 0, $x, $y, $textColor, $fontPath, $letter);

        // Same naming pattern
        $filename = $prefix . date('d_m_Y_') . rand(1111, 9999) . '.png';
        $fullPath = base_path('storage/app/public/' . $folder . '/' . $filename);

        imagepng($img, $fullPath);
        imagedestroy($img);

        return $filename;
    }

    // API's Functions
    public function API_Response($status_code, $message, $array = [], $pagination = '')
    {
        try {
            $data['status'] = $status_code;
            $data['message'] = $message;

            if ($status_code == 200) {
                $data['result'] = $array;
            } else {
                $data['errors'] = $message;
                $data['result'] = [];
            }

            if ($pagination) {
                $data['total_rows'] = $pagination['total_rows'];
                $data['total_page'] = $pagination['total_page'];
                $data['current_page'] = $pagination['current_page'];
                $data['more_page'] = $pagination['more_page'];
            }
            return $data;
        } catch (Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'Something went wrong. Please try again.',
                'errors' => 'Something went wrong. Please try again.',
                'result' => [],
            ]);
        }
    }
    public function API_Error($exception = null)
    {
        if ($exception) {
            \Illuminate\Support\Facades\Log::error($exception);
        }

        return response()->json([
            'status' => 400,
            'message' => 'Something went wrong. Please try again.',
            'errors' => 'Something went wrong. Please try again.',
            'result' => [],
        ]);
    }
    public function more_page($current_page, $page_size)
    {
        try {
            $more_page = false;
            if ($current_page < $page_size) {
                $more_page = true;
            }
            return $more_page;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function pagination_array($total_rows, $page_size, $current_page, $more_page)
    {
        try {
            $array['total_rows'] = $total_rows;
            $array['total_page'] = $page_size;
            $array['current_page'] = (int) $current_page;
            $array['more_page'] = $more_page;

            return $array;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    ///
    public function user_name($string)
    {
        $rand_number = rand(1111, 9999);
        $user_name = '@' . $string . '_' . $rand_number;

        $check = Customer::where('user_name', $user_name)->first();
        if (isset($check) && $check != null) {
            $this->user_name($string);
        }
        return $user_name;
    }
    public function delivery_boy_user_name($string)
    {
        $rand_number = rand(1111, 9999);
        $user_name = '@' . $string . '_' . $rand_number;

        $check = DeliveryBoy::where('user_name', $user_name)->first();
        if (isset($check) && $check != null) {
            $this->user_name($string);
        }
        return $user_name;
    }
    public function GetDateTime($date)
    {
        if ($date != null) {
            $timestamp = strtotime($date);
            $formattedTime = date('j M, Y', $timestamp);
        } else {
            $formattedTime = "";
        }

        return $formattedTime;
    }
    public function GetCityByZipCode($zipcode_id)
    {
        $get = ZipCode::where('id', $zipcode_id)->with('city')->first();
        if ($get->city != null) {
            $cityName = $get->city->name;
        } else {
            $cityName = "-";
        }
        return $cityName;
    }
    public function CompensationType($compensation_id)
    {
        $get = Compensation::where('id', $compensation_id)->first();

        $array['type'] = $get['type'];
        $array['amount'] = $get['amount'];

        return $array;
    }
    function Item_Amenity_Value_Add($value, $items_id)
    {
        try {
            $fieldType = (int)$value['field_type'];
            $insert_value = [
                'attributes_id' => $value['amenity_id'],
                'item_id'       => $items_id,
                'field_type'    => $fieldType,
                'option'        => '',
            ];

            if ($fieldType === 1 || $fieldType === 2) {
                if (!empty($value['field_value'])) {
                    $insert_value['option'] = $value['field_value'];
                    ItemsAttributes::insert($insert_value);
                }
            } elseif ($fieldType === 6) {

                if (!empty($value['icon'])) {
                    $insert_value['option'] = $this->saveImage($value['icon'], $this->folder_product, 'other_');
                    ItemsAttributes::insert($insert_value);
                }
            } elseif (in_array($fieldType, [3, 4, 5])) {

                if (isset($value['field_value'])) {

                    if ($fieldType === 3 || $fieldType === 5) {
                        $fieldValue = is_array($value['field_value']) ? implode(",", $value['field_value']) : $value['field_value'];
                    } else {
                        $fieldValue = $value['field_value'];
                    }

                    $insert_value['option'] = $fieldValue;

                    ItemsAttributes::insert($insert_value);
                }
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    function Update_Item_Amenity_value($additional_array, $item_id)
    {
        try {

            $not_delete_ids = array();
            $not_delete_img = array();

            foreach ($additional_array as $value) {

                $fieldType  = (int)$value['field_type'];
                $amenityId  = $value['amenity_id'];

                $old_values_before_delete = ItemsAttributes::where('attributes_id', $amenityId)->where('item_id', $item_id)->get()->pluck('option')->toArray();

                // delete old records first (fresh insert logic)
                ItemsAttributes::where('attributes_id', $amenityId)->where('item_id', $item_id)->delete();

                $insert = [
                    'attributes_id' => $amenityId,
                    'item_id'       => $item_id,
                    'field_type'    => $fieldType,
                    'option'        => '',
                ];

                if (in_array($fieldType, [1, 2])) {

                    if (!empty($value['field_value'])) {

                        $insert['option'] = $value['field_value'];
                        $id = ItemsAttributes::insertGetId($insert);
                        $not_delete_ids[] = $id;
                    }
                } elseif ($fieldType === 6) {

                    if (!empty($value['icon'])) {

                        $insert['option'] = $this->saveImage($value['icon'], $this->folder_product, 'other_');

                        // DELETE old stored file
                        foreach ($old_values_before_delete as $oldimg) {
                            $this->deleteImageToFolder($this->folder_product, $oldimg);
                        }
                    } elseif (!empty($value['old_icon'])) {
                        $insert['option'] = basename($value['old_icon']);
                    } else {
                        $insert['option'] = "";
                    }

                    $id = ItemsAttributes::insertGetId($insert);
                    $not_delete_ids[] = $id;

                    if (!empty($insert['option'])) {
                        $not_delete_img[] = $insert['option'];
                    }
                } elseif (in_array($fieldType, [3, 4, 5])) {

                    if (isset($value['field_value'])) {

                        $fieldValueArr = is_array($value['field_value']) ? $value['field_value'] : [$value['field_value']];
                        $fieldValueArr = array_filter($fieldValueArr);

                        if ($fieldType === 3 || $fieldType === 5) {
                            $insert['option'] = implode(",", $fieldValueArr);
                        }
                        // Radio(4) → single
                        else {
                            $insert['option'] = $fieldValueArr[0] ?? '';
                        }

                        $id = ItemsAttributes::insertGetId($insert);
                        $not_delete_ids[] = $id;
                    }
                }
            }

            $oldItems = ItemsAttributes::where('item_id', $item_id)->whereNotIn('id', $not_delete_ids)->get();

            foreach ($oldItems as $old) {

                $oldFile = basename($old->option);
                if (!in_array($oldFile, $not_delete_img)) {
                    $this->deleteImageToFolder($this->folder_product, $oldFile);
                }
                $old->delete();
            }
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    function SaveItemAttributes($value, $item_id)
    {
        try {

            if (empty($value['field_type']) || empty($value['attribute_id'])) {
                return;
            }

            $fieldType = $value['field_type'];
            $fieldValue = '';
            $icon = '';

            // 1-Text, 2-Numeric, 4-Radio, 6-File Upload
            if (in_array($fieldType, [1, 2, 4])) {
                $fieldValue = $value['field_value'] ?? '';
            }

            // 3-DropDown, 5-Checkbox (Multiple values)
            elseif (in_array($fieldType, [3, 5])) {

                if (!empty($value['field_value']) && is_array($value['field_value'])) {
                    $fieldValue = implode(",", $value['field_value']);
                }
            }
            // 6-File Upload
            elseif ($fieldType == 6) {
                $icon = $value['icon'] ?? '';
                $fieldValue = $icon;
            }

            $insertData = [
                'attributes_id' => $value['attribute_id'],
                'item_id' => $item_id,
                'option' => $fieldValue,
                'field_type' => $fieldType,
            ];

            ItemsAttributes::insert($insertData);
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function UpdateMultiImages($images, $itemId)
    {
        // Existing DB Images
        $oldImages = ItemsImages::where('item_id', $itemId)->get();
        $oldImageNames = $oldImages->pluck('image')->toArray();

        foreach ($images as $imageName) {

            if (!in_array($imageName, $oldImageNames)) {

                ItemsImages::insert([
                    'item_id' => $itemId,
                    'image' => $imageName,
                    'status' => 1,
                ]);
            }
        }

        foreach ($oldImages as $old) {

            if (!in_array($old->image, $images)) {

                if (!empty($old->image)) {
                    $this->deleteImageToFolder($this->folder_product, basename($old->image));

                    $this->deleteImageToFolder(
                        $this->folder_product,
                        basename($old->image)
                    );
                }

                $old->delete();
            }
        }
    }
    public function UpdateItemFaq($faqs, $itemId)
    {
        // Get old FAQs
        $oldFaqs = ItemsFaq::where('item_id', $itemId)->get();

        $oldFaqArray = [];

        foreach ($oldFaqs as $faq) {
            $oldFaqArray[] = [
                'question' => $faq->question,
                'answer' => $faq->answer
            ];
        }

        foreach ($faqs as $faq) {

            if (!in_array($faq, $oldFaqArray)) {

                ItemsFaq::insert([
                    'item_id' => $itemId,
                    'question' => $faq['question'] ?? '',
                    'answer' => $faq['answer'] ?? '',
                    'status' => 1,
                ]);
            }
        }

        foreach ($oldFaqs as $old) {

            $exists = false;

            foreach ($faqs as $newFaq) {
                if (
                    $old->question == $newFaq['question'] &&
                    $old->answer == $newFaq['answer']
                ) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $old->delete();
            }
        }
    }
    public function UpdateItemAttributes($attributes, $item_id)
    {
        try {

            $not_delete_ids = [];
            $not_delete_img = [];

            foreach ($attributes as $value) {

                $fieldType   = (int)($value['field_type'] ?? 0);
                $attributeId = $value['attribute_id'] ?? 0;

                if (!$attributeId) continue;

                // Get old values before delete
                $old_values_before_delete = ItemsAttributes::where('attributes_id', $attributeId)
                    ->where('item_id', $item_id)
                    ->pluck('option')
                    ->toArray();

                // Delete old records first (fresh insert logic)
                ItemsAttributes::where('attributes_id', $attributeId)
                    ->where('item_id', $item_id)
                    ->delete();

                $insert = [
                    'attributes_id' => $attributeId,
                    'item_id'       => $item_id,
                    'field_type'    => $fieldType,
                    'option'        => '',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];

                /* ================= TEXT / NUMERIC ================= */

                if (in_array($fieldType, [1, 2])) {

                    if (!empty($value['field_value'])) {

                        $insert['option'] = $value['field_value'];
                        $id = ItemsAttributes::insertGetId($insert);
                        $not_delete_ids[] = $id;
                    }
                }

                /* ================= FILE TYPE ================= */ elseif ($fieldType === 6) {

                    $icon    = $value['icon'] ?? '';
                    $oldIcon = $value['old_icon'] ?? '';

                    if (!empty($icon)) {

                        // Delete old stored file
                        foreach ($old_values_before_delete as $oldimg) {
                            if (!empty($oldimg)) {
                                $this->deleteImageToFolder(
                                    $this->folder_product,
                                    basename($oldimg)
                                );
                            }
                        }

                        // API ma icon already uploaded file name hoy che
                        $insert['option'] = basename($icon);
                    } elseif (!empty($oldIcon)) {
                        $insert['option'] = basename($oldIcon);
                    } else {
                        $insert['option'] = "";
                    }

                    $id = ItemsAttributes::insertGetId($insert);
                    $not_delete_ids[] = $id;

                    if (!empty($insert['option'])) {
                        $not_delete_img[] = $insert['option'];
                    }
                }

                /* ================= DROPDOWN / RADIO / CHECKBOX ================= */ elseif (in_array($fieldType, [3, 4, 5])) {

                    if (isset($value['field_value'])) {

                        $fieldValueArr = is_array($value['field_value'])
                            ? $value['field_value']
                            : [$value['field_value']];

                        $fieldValueArr = array_filter($fieldValueArr);

                        if ($fieldType == 3 || $fieldType == 5) {
                            $insert['option'] = implode(",", $fieldValueArr);
                        } else { // Radio (4)
                            $insert['option'] = $fieldValueArr[0] ?? '';
                        }

                        $id = ItemsAttributes::insertGetId($insert);
                        $not_delete_ids[] = $id;
                    }
                }
            }

            /* ================= FINAL CLEANUP ================= */

            $oldItems = ItemsAttributes::where('item_id', $item_id)
                ->whereNotIn('id', $not_delete_ids)
                ->get();

            foreach ($oldItems as $old) {

                if ($old->field_type == 6 && !empty($old->option)) {

                    $oldFile = basename($old->option);

                    if (!in_array($oldFile, $not_delete_img)) {
                        $this->deleteImageToFolder(
                            $this->folder_product,
                            $oldFile
                        );
                    }
                }

                $old->delete();
            }
        } catch (Exception $e) {

            return response()->json([
                'status' => 400,
                'errors' => $e->getMessage()
            ]);
        }
    }
    public function IncreaseUsedTags($array)
    {

        foreach ($array as $key => $value) {
            Hashtag::where('id', $value)->increment('total_used', 1);
        }
    }
    public function SellerAvgRating($seller_id)
    {
        $rating = Review::where('content_id', $seller_id)->where('type', 2)->avg('rating');
        return $rating;
    }
    public function SellerTotalSales($seller_id)
    {
        $totalSales = Orders::where('seller_id', $seller_id)->where('payment_status', 2)->where('order_status', 6)->count();
        return $totalSales;
    }
    public function ActiveExpriedOffers()
    {
        $offers = Offers::latest()->get();

        $currentDate = date('Y-m-d');

        foreach ($offers as $offer) {
            $validFrom = date('Y-m-d', strtotime($offer->valid_from));
            $validUntil = date('Y-m-d', strtotime($offer->valid_until));

            if ($currentDate < $validFrom) {
                $status = 0; // InActive
            } elseif ($currentDate >= $validFrom && $currentDate <= $validUntil) {
                $status = 1; // Active
            } elseif ($currentDate > $validUntil) {
                $status = 2; // Expired
                Banner::where('content_type', '1')->where('content_id', $offer->id)->delete();
            }

            Offers::where('id', $offer['id'])->update(['status' => $status]);
        }
    }
    public function ActiveExpriedPromocodes()
    {
        $offers = Promocode::latest()->get();

        $currentDate = date('Y-m-d');

        foreach ($offers as $offer) {
            $validFrom = date('Y-m-d', strtotime($offer->valid_from));
            $validUntil = date('Y-m-d', strtotime($offer->valid_until));

            if ($currentDate < $validFrom) {
                $status = 0; // InActive
            } elseif ($currentDate >= $validFrom && $currentDate <= $validUntil) {
                $status = 1; // Active
            } elseif ($currentDate > $validUntil) {
                $status = 2; // Expired
            }
            if ($offer->usage_limit == 1) {

                $usedCustomerCount = Orders::where('promo_codes_id', $offer->id)
                    ->distinct('customer_id')
                    ->count('customer_id');

                if ($usedCustomerCount >= $offer->user_limit_per_user) {
                    $status = 2;
                }
            }

            Promocode::where('id', $offer['id'])->update(['status' => $status]);
        }
    }
    public function DetailsGetTagsName($tag_id)
    {
        $Ids = explode(',', $tag_id);

        $data = Hashtag::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(" | ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function DetailsGetTaxsName($tax_id)
    {
        $Ids = explode(',', $tax_id);

        $data = Taxes::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(" | ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetOptionSet($option)
    {
        $explod_option = explode(",", $option);

        $data = '<div class="text-left">';
        for ($i = 0; $i < count($explod_option); $i++) {
            $data .= '<i class="fa-solid fa-arrow-right mr-2"></i>' . $explod_option[$i] . '<br>';
        }
        $data .= '</div>';
        return $data;
    }
    public function avg_rating($id, $type) // Content Type :- 1- Product, 2- Seller
    {
        $reviews = Review::where('content_id', $id)->where('type', $type)->get();

        $reviewCount = $reviews->count();
        if ($reviewCount == 0) {
            return "0.0";
        }
        $totalRating = $reviews->sum('rating');
        $avg = $totalRating / $reviewCount;


        return number_format($avg, 1);
    }
    public function review_count($id, $type) // Content Type :- 1- Product, 2- Sellers
    {
        $review_count = 0;
        if ($type == 1) {

            $review_count = Review::where('content_id', $id)->get()->count();
        }
        return $review_count;
    }
    public function is_bookmark($customer_id, $item_id)
    {
        try {

            $is_bookmark = 0;
            $data = Wishlist::where('item_id', $item_id)->where('customer_id', $customer_id)->where('status', 1)->first();
            if ($data != null && isset($data)) {
                $is_bookmark = 1;
            }
            return $is_bookmark;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function is_addtocart($customer_id, $item_id)
    {
        try {

            $is_cart = 0;
            $data = AddToCart::where('item_id', $item_id)->where('customer_id', $customer_id)->where('status', 1)->first();
            if ($data != null && isset($data)) {
                $is_cart = 1;
            }
            return $is_cart;
        } catch (Exception $e) {
            return response()->json(array('status' => 400, 'errors' => $e->getMessage()));
        }
    }
    public function checkHashTag($hashTag)
    {
        try {

            if (strpos($hashTag, '#') !== false) {

                $remove = substr($hashTag, strpos($hashTag, '#'));
                $tag = explode('#', $remove);

                $id = [];
                if (count($tag) > 0) {
                    foreach ($tag as $key => $value) {

                        if ($value && $value != "") {

                            $value = ltrim($value);
                            $tag = explode(' ', $value)[0];

                            $row = Hashtag::where('name', $tag)->first();
                            if (isset($row['id'])) {

                                $id[] = $row['id'];
                                $row->increment('total_used', 1);
                            } else {

                                $data['name'] = $tag;
                                $data['total_used'] = 1;
                                $data['status'] = 1;
                                $hashtag_id = Hashtag::insertGetId($data);
                                $id[] = $hashtag_id;
                            }
                        }
                    }
                    return $id;
                }
            } else {
                return array();
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Common Functions
    public function GetServicableZone($zone_id)
    {
        if (empty($zone_id)) {
            return [];
        }

        $zoneIds = array_filter(array_map('trim', explode(',', $zone_id)));

        $zones = Zones::whereIn('id', $zoneIds)->get();

        $mainData = [];

        foreach ($zones as $zone) {

            $zoneValues = ZonesValue::where('zone_id', $zone->id)
                ->with('zipcode')
                ->get();

            $zipcodes = $zoneValues->map(function ($zoneValue) {

                $zipcode = trim($zoneValue->zipcode->zipcode ?? '');
                $area    = trim($zoneValue->zipcode->area_name ?? '');

                if ($zipcode && $area) {
                    return "{$zipcode} - {$area}";
                }

                return $zipcode ?: '-';
            })
                ->filter()               // remove empty values
                ->unique()               // avoid duplicates
                ->values()
                ->toArray();

            $mainData[] = [
                'zone_name' => trim($zone->zone_name),
                'zipcodes' => implode(', ', $zipcodes),
            ];
        }

        return $mainData;
    }
    public function GetDeliveryBoyData($data)
    {
        $get = $this->GetServicableZone($data['zone_id']);
        $list = [
            'email' => $data->email,
            'delivery_boy_name' => $data->full_name,
            'zone_data' => $get,
        ];

        return $list;
    }
    public function GetDeliveryBoy($id)
    {
        $get = DeliveryBoy::where('id', $id)->first();
        return $get;
    }
    public function AssignOrderDetails($delivery_boy_id, $order_id, $array)
    {

        $delivery_boy = DeliveryBoy::where('id', $delivery_boy_id)->first();
        $order = Orders::where('group_order_id', $order_id)->with('customer', 'customer_address')->first();
        $vehicle = Vehicle::where('id', $array['vehicle_id'])->first();
        $customer = $order->customer;
        $c_address = $order->customer_address;

        $list = [
            'email' => $delivery_boy['email'],
            'delivery_boy_name' => $delivery_boy['full_name'],
            'customer_name' => $customer['full_name'],
            'delivery_address' => $c_address['address'],
            'order_no' => $order['group_order_id'],
            'total_amount' => $order['total_amount'],
            'vehicle_name' => $vehicle['name'],
            'vehicle_number' => $vehicle['number'],
            'pickup_time' => $array['pickup_time'],
            'pickup_location' => $array['pickup_location'],
        ];

        return $list;
    }
    public function GetOrderAssignDetails($delivery_boy_id, $vehicle_id, $group_order_id)
    {
        $order = Orders::where('group_order_id', $group_order_id)->with('customer', 'customer_address')->first();
        $items = PickupItems::where('group_order_id', $group_order_id)->with('item')->get();
        $delivery_boy = DeliveryBoy::find($delivery_boy_id);
        $vehicle = Vehicle::find($vehicle_id);

        $itemsList = [];
        foreach ($items as $value) {
            $itemsList[] = [
                'order_number'   => $value->order_number ?? '-',
                'item_title'     => $value->item->name ?? '-',
                'pickup_time'    => $value->pickup_time ?? '-',
                'pickup_address' => $value->pickup_location ?? '-',
            ];
        }

        $list = [
            'email' => $delivery_boy['email'],
            'customer_name' => $order->customer->user_name,
            'delivery_boy_name' => $delivery_boy['user_name'],
            'vehicle_name' => $vehicle['name'],
            'vehicle_number' => $vehicle['number'],
            'group_order_id' => $order['group_order_id'],
            'total_amount' => $order['total_payable_amount'],
            'delivery_address' => trim($order->customer_address->address . ', ' . ($order->customer_address->area ?? '') . ', ' . ($order->customer_address->city ?? '') . ', ' . ($order->customer_address->state ?? '') . ', ' . ($order->customer_address->country ?? '') . ' - ' . ($order->customer_address->pincode ?? '')),
            'items' => $itemsList,
        ];
        return $list;
    }
    public function ReturnOrderDetails($delivery_boy_id, $order_id, $array)
    {
        $delivery_boy = DeliveryBoy::where('id', $delivery_boy_id)->first();
        $order = Orders::where('id', $order_id)->with('customer', 'customer_address')->first();
        $vehicle = Vehicle::where('id', $array['vehicle_id'])->first();
        $customer = $order->customer;
        $c_address = $order->customer_address;

        $list = [
            'email' => $delivery_boy['email'],
            'delivery_boy_name' => $delivery_boy['full_name'],
            'customer_name' => $customer['full_name'],
            'delivery_address' => $c_address['address'],
            'order_no' => $order['group_order_id'],
            'total_amount' => $order['total_amount'],
            'vehicle_name' => $vehicle['name'],
            'vehicle_number' => $vehicle['number'],
            'return_pickup_time' => $array['return_pickup_time'],
        ];
        return $list;
    }
    public function UpdateOrdersDetails($customer_id, $order_no, $status)
    {
        $customer = Customer::where('id', $customer_id)->first();

        if ($status == 1) {
            $orderStatus = __('label.pending');
        } elseif ($status == 2) {
            $orderStatus = __('label.confirmed');
        } elseif ($status == 3) {
            $orderStatus = __('label.shipped');
        } elseif ($status == 4) {
            $orderStatus = __('label.out_for_delivery');
        } elseif ($status == 5) {
            $orderStatus = __('label.delivered');
        } elseif ($status == 6) {
            $orderStatus = __('label.cancelled');
        } elseif ($status == 7) {
            $orderStatus = __('label.return_requested');
        } elseif ($status == 8) {
            $orderStatus = __('label.return_inprocess');
        } elseif ($status == 9) {
            $orderStatus = __('label.returned');
        } elseif ($status == 10) {
            $orderStatus = __('label.returnedrejected');
        }

        $list = [
            'email' => $customer['email'],
            'customer_name' => $customer['full_name'],
            'order_no' => $order_no,
            'order_status' => $orderStatus,
        ];

        return $list;
    }
    public function SectionQueryData($customer_id, $section_type, $content_type, $screen_layout, $content_ids, $category_id, $sub_category_id, $brand_id, $seller_id, $product_content_type, $selling_type, $include_low_price, $include_high_price, $order_by_upload, $order_by_like, $no_of_content)
    {
        if ($content_type == 1) {
            $content = Category::where('status', 1)->orderBy('sort_order', 'asc');
            $category_ids = explode(',', $category_id);

            $content->whereIn('id', $category_ids);

            $query =  $content->get();

            for ($j = 0; $j < count($query); $j++) {
                if ($screen_layout == 'category_grid') {

                    $totalSubCatCount = Category::where('parent_category_id', $query[$j]['id'])->count();
                    $subCat = Category::select('id', 'image', 'name')->where('parent_category_id', $query[$j]['id'])->take(4)->where('status', 1)->get();
                    foreach ($subCat as $value) {
                        $value['image'] = $this->getImage($this->folder_category, $value['image']);
                    }
                    $query[$j]['sub_cat'] = $subCat;
                    $query[$j]['total_sub_cat'] = $totalSubCatCount;
                }
                $this->imageNameToUrl(array($query[$j]), 'image', $this->folder_category);
            }
            return $query;
        } elseif ($content_type == 2) {
            $content = Items::where('is_approved', 1)->where('status', 1);
            if ($product_content_type == 1) {
                if ($section_type == 1) {
                    $content_ids = explode(',', $content_ids);
                    $content->whereIn('id', $content_ids);

                    $query =  $content->get();

                    for ($j = 0; $j < count($query); $j++) {

                        $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                        $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                        if ($query[$j]['video_type'] == 1) {
                            $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                        }
                        $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                        $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                        $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                        $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                        $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                        $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                        $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                        $query[$j]['final_price'] = $query[$j]['price'];
                        $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                        $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                        $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                        $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                    }
                    return $query;
                } else if ($section_type == 0) {

                    if ($category_id != "") {
                        $content->where('category_id', $category_id);
                    }
                    if ($sub_category_id != 0) {
                        $content->where('sub_category_id', $sub_category_id);
                    }
                    if ($brand_id != 0) {
                        $content->where('brand_id', $brand_id);
                    }
                    if ($seller_id != 0) {
                        $content->where('seller_id', $seller_id);
                    }

                    if ($order_by_like == 2) {
                        $wishlistItemIds = DB::table('tbl_wishlist')
                            ->select('item_id', DB::raw('COUNT(*) as wishlist_count'))
                            ->where('status', 1)
                            ->groupBy('item_id')
                            ->orderByDesc('wishlist_count')
                            ->pluck('item_id')
                            ->toArray();

                        if (!empty($wishlistItemIds)) {
                            $content->whereIn('id', $wishlistItemIds);

                            // Maintain wishlist order
                            $order = implode(',', $wishlistItemIds);
                            $content->orderByRaw("FIELD(id, $order)");
                        }
                    }
                    if ($order_by_upload == 2) {
                        $content->orderBy('id', 'desc');
                    }
                    $query =  $content->take($no_of_content)->get();

                    for ($j = 0; $j < count($query); $j++) {

                        $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                        $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                        if ($query[$j]['video_type'] == 1) {
                            $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                        }
                        $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                        $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                        $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                        $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                        $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                        $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                        $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                        $query[$j]['final_price'] = $query[$j]['price'];
                        $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                        $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                        $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                        $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                    }
                    return $query;
                }
            } elseif ($product_content_type == 2) {
                $topSellerStats = Orders::select('seller_id', DB::raw('SUM(order_quantity) as total_sold'))
                    ->where('payment_status', 2) // Paid
                    ->where('order_status', 5)   // Delivered
                    ->groupBy('seller_id')
                    ->orderByDesc('total_sold')
                    ->get();

                $sellerIds = $topSellerStats->pluck('seller_id')->toArray();

                $query = Items::whereIn('seller_id', $sellerIds)->limit($no_of_content)->get();
                for ($j = 0; $j < count($query); $j++) {

                    $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                    $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                    if ($query[$j]['video_type'] == 1) {
                        $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                    }
                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 3) {
                $query = Orders::select('item_id', DB::raw('SUM(order_quantity) as total_sold'))
                    ->where('payment_status', 2)
                    ->where('order_status', 5);

                if ($selling_type == 1) {
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                } elseif ($selling_type == 2) {
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                } elseif ($selling_type == 3) {
                    $query->whereBetween('created_at', [now()->subYear(), now()]);
                }

                $topSellingItems = $query->groupBy('item_id')
                    ->orderByDesc('total_sold')
                    ->get();

                $array = [];
                foreach ($topSellingItems as $value) {
                    $array[] = [
                        'item_id' => $value->item_id,
                        'total_sold' => $value->total_sold,
                    ];
                }

                $sortedArray = collect($array)->sortByDesc('total_sold')->values();
                $itemIds = $sortedArray->pluck('item_id')->toArray();

                // Get items using item IDs and limit by $noOfContent
                $query = Items::whereIn('id', $itemIds)
                    ->limit($no_of_content)
                    ->get();

                for ($j = 0; $j < count($query); $j++) {

                    $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                    $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                    if ($query[$j]['video_type'] == 1) {
                        $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                    }
                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 4) {

                $topRatedItems = DB::table('tbl_review')
                    ->select('content_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_reviews'))
                    ->where('type', 1) // Type 1 = Product
                    ->where('status', 1)
                    ->groupBy('content_id')
                    ->orderByDesc('avg_rating')
                    ->orderByDesc('total_reviews')
                    ->get();

                $itemIds = $topRatedItems->pluck('content_id')->toArray();

                $ratingMap = $topRatedItems->keyBy('content_id');

                $query = Items::whereIn('id', $itemIds)->limit($no_of_content)->get();

                for ($j = 0; $j < count($query); $j++) {

                    $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                    $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                    if ($query[$j]['video_type'] == 1) {
                        $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                    }
                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $itemId = $query[$j]['id'];
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = isset($ratingMap[$itemId]) ? number_format($ratingMap[$itemId]->avg_rating, 1) : 0;
                    $query[$j]['total_reviews'] = isset($ratingMap[$itemId]) ? $ratingMap[$itemId]->total_reviews : 0;
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 5) {

                $content = Items::where('is_approved', 1)->where('status', 1);
                $content->whereBetween('price', [$include_low_price, $include_high_price]);

                if ($order_by_upload == 2) {
                    $content->orderBy('id', 'desc');
                }
                $query =  $content->limit($no_of_content)->get();
                for ($j = 0; $j < count($query); $j++) {

                    $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                    $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                    if ($query[$j]['video_type'] == 1) {
                        $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                    }
                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 6) {

                $interests = TrackIntrest::where('customer_id', $customer_id)
                    ->orderBy('count', 'desc')
                    ->get();

                // Step 2: Fetch interested item IDs
                $interestedItemIds = $interests->pluck('item_id')->toArray();

                // Step 3: Get items user is interested in (sorted by interest count)
                $interestedItems = collect();
                foreach ($interests as $interest) {
                    $item = Items::find($interest->item_id);
                    if ($item) {
                        $item->interest_count = $interest->count; // Optional: attach count
                        $interestedItems->push($item);
                    }
                }
                $categoryIds = $interests->pluck('category_id')->unique()->toArray();
                $subCategoryIds = $interests->pluck('sub_category_id')->unique()->toArray();

                // Step 5: Get items where category matches but item not already in interest
                $categoryMatchItems = Items::whereIn('category_id', $categoryIds)
                    ->whereNotIn('id', $interestedItemIds)->where('is_approved', 1)->where('status', 1)
                    ->get();

                // Step 6: Get items where sub_category matches but item not already in interest or category matched
                $excludeIds = array_merge($interestedItemIds, $categoryMatchItems->pluck('id')->toArray());

                $subCategoryMatchItems = Items::whereIn('sub_category_id', $subCategoryIds)
                    ->whereNotIn('id', $excludeIds)->where('is_approved', 1)->where('status', 1)
                    ->get();

                // Final: Merge all, interested first, then category matched, then sub_category matched
                $data = $interestedItems->merge($categoryMatchItems)->merge($subCategoryMatchItems)->values();
                $query = $data->take($no_of_content);
                for ($j = 0; $j < count($query); $j++) {

                    $this->imageNameToUrl(array($query[$j]), 'portrait_img', $this->folder_item);
                    $this->imageNameToUrl(array($query[$j]), 'landscape_img', $this->folder_item);
                    if ($query[$j]['video_type'] == 1) {
                        $this->imageNameToUrl(array($query[$j]), 'url', $this->folder_item);
                    }
                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }

                return $query;
            }
        }
    }
    public function SectionDetailsQuery($customer_id, $section_type, $content_type, $screen_layout, $content_ids, $category_id, $sub_category_id, $brand_id, $seller_id, $product_content_type, $selling_type, $include_low_price, $include_high_price, $order_by_upload, $order_by_like, $no_of_content)
    {
        if ($content_type == 1) {
            $content = Category::where('status', 1)->orderBy('sort_order', 'asc');
            $category_ids = explode(',', $category_id);

            $content->whereIn('id', $category_ids);

            $query =  $content->get()->toArray();

            for ($j = 0; $j < count($query); $j++) {

                if ($screen_layout == 'category_grid') {
                    $totalSubCatCount = Category::where('parent_category_id', $query[$j]['id'])->count();

                    $subCat = Category::select('id', 'image', 'name')->where('parent_category_id', $query[$j]['id'])->take(4)->where('status', 1)->get();
                    foreach ($subCat as $value) {
                        $value['image'] = $this->getImage($this->folder_category, $value['image']);
                    }
                    $query[$j]['sub_cat'] = $subCat;
                    $query[$j]['total_sub_cat'] = $totalSubCatCount;
                }
                $query[$j]['image'] = $this->getImage($this->folder_category, $query[$j]['image']);
            }
            return $query;
        } elseif ($content_type == 2) {
            $content = Items::where('is_approved', 1)->where('status', 1);
            if ($product_content_type == 1) {
                if ($section_type == 1) {
                    $content_ids = explode(',', $content_ids);
                    $content->whereIn('id', $content_ids);

                    $query =  $content->get()->toArray();

                    for ($j = 0; $j < count($query); $j++) {

                        $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                        $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                        if ($query[$j]['video_type'] == 1) {
                            $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                        }
                        $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                        $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                        $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                        $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                        $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                        $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                        $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                        $query[$j]['final_price'] = $query[$j]['price'];
                        $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                        $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                        $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                        $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                    }
                    return $query;
                } else if ($section_type == 0) {


                    if ($category_id != 0) {
                        $content->where('category_id', $category_id);
                    }
                    if ($sub_category_id != 0) {
                        $content->where('sub_category_id', $sub_category_id);
                    }
                    if ($brand_id != 0) {
                        $content->where('brand_id', $brand_id);
                    }
                    if ($seller_id != 0) {
                        $content->where('seller_id', $seller_id);
                    }

                    if ($order_by_like == 2) {
                        $wishlistItemIds = DB::table('tbl_wishlist')
                            ->select('item_id', DB::raw('COUNT(*) as wishlist_count'))
                            ->where('status', 1)
                            ->groupBy('item_id')
                            ->orderByDesc('wishlist_count')
                            ->pluck('item_id')
                            ->toArray();

                        if (!empty($wishlistItemIds)) {
                            $content->whereIn('id', $wishlistItemIds);

                            // Maintain wishlist order
                            $order = implode(',', $wishlistItemIds);
                            $content->orderByRaw("FIELD(id, $order)");
                        }
                    }
                    if ($order_by_upload == 2) {
                        $content->orderBy('id', 'desc');
                    }
                    $query =  $content->take($no_of_content)->get()->toArray();

                    for ($j = 0; $j < count($query); $j++) {

                        $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                        $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                        if ($query[$j]['video_type'] == 1) {
                            $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                        }
                        $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                        $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                        $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                        $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                        $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                        $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                        $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                        $query[$j]['final_price'] = $query[$j]['price'];
                        $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                        $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                        $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                        $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                    }
                    return $query;
                }
            } elseif ($product_content_type == 2) {
                $topSellerStats = Orders::select('seller_id', DB::raw('SUM(order_quantity) as total_sold'))
                    ->where('payment_status', 2) // Paid
                    ->where('order_status', 5)   // Delivered
                    ->groupBy('seller_id')
                    ->orderByDesc('total_sold')
                    ->get();

                $sellerIds = $topSellerStats->pluck('seller_id')->toArray();
                $query = Items::whereIn('seller_id', $sellerIds)->limit($no_of_content)->get()->toArray();
                for ($j = 0; $j < count($query); $j++) {

                    $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                    $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                    if ($query[$j]['video_type'] == 1) {
                        $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                    }

                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 3) {
                $query = Orders::select('item_id', DB::raw('SUM(order_quantity) as total_sold'))
                    ->where('payment_status', 2)
                    ->where('order_status', 5);

                if ($selling_type == 1) {
                    $query->whereBetween('created_at', [now()->subWeek(), now()]);
                } elseif ($selling_type == 2) {
                    $query->whereBetween('created_at', [now()->subMonth(), now()]);
                } elseif ($selling_type == 3) {
                    $query->whereBetween('created_at', [now()->subYear(), now()]);
                }

                $topSellingItems = $query->groupBy('item_id')
                    ->orderByDesc('total_sold')
                    ->get();

                $array = [];
                foreach ($topSellingItems as $value) {
                    $array[] = [
                        'item_id' => $value->item_id,
                        'total_sold' => $value->total_sold,
                    ];
                }

                $sortedArray = collect($array)->sortByDesc('total_sold')->values();
                $itemIds = $sortedArray->pluck('item_id')->toArray();

                // Get items using item IDs and limit by $noOfContent
                $query = Items::whereIn('id', $itemIds)
                    ->limit($no_of_content)
                    ->get()->toArray();

                for ($j = 0; $j < count($query); $j++) {

                    $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                    $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                    if ($query[$j]['video_type'] == 1) {
                        $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                    }

                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 4) {

                $topRatedItems = DB::table('tbl_review')
                    ->select('content_id', DB::raw('AVG(rating) as avg_rating'), DB::raw('COUNT(id) as total_reviews'))
                    ->where('type', 1) // Type 1 = Product
                    ->where('status', 1)
                    ->groupBy('content_id')
                    ->orderByDesc('avg_rating')
                    ->orderByDesc('total_reviews')
                    ->get();

                $itemIds = $topRatedItems->pluck('content_id')->toArray();

                $ratingMap = $topRatedItems->keyBy('content_id');

                $query = Items::whereIn('id', $itemIds)->limit($no_of_content)->get()->toArray();

                for ($j = 0; $j < count($query); $j++) {

                    $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                    $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                    if ($query[$j]['video_type'] == 1) {
                        $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                    }

                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $itemId = $query[$j]['id'];
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = isset($ratingMap[$itemId]) ? number_format($ratingMap[$itemId]->avg_rating, 1) : 0;
                    $query[$j]['total_reviews'] = isset($ratingMap[$itemId]) ? $ratingMap[$itemId]->total_reviews : 0;
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 5) {

                $content = Items::where('is_approved', 1)->where('status', 1);
                $content->whereBetween('price', [$include_low_price, $include_high_price]);

                if ($order_by_upload == 2) {
                    $content->orderBy('id', 'desc');
                }
                $query =  $content->limit($no_of_content)->get()->toArray();
                for ($j = 0; $j < count($query); $j++) {

                    $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                    $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                    if ($query[$j]['video_type'] == 1) {
                        $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                    }

                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }
                return $query;
            } elseif ($product_content_type == 6) {

                $interests = TrackIntrest::where('customer_id', $customer_id)
                    ->orderBy('count', 'desc')
                    ->get();

                // Step 2: Fetch interested item IDs
                $interestedItemIds = $interests->pluck('item_id')->toArray();

                // Step 3: Get items user is interested in (sorted by interest count)
                $interestedItems = collect();
                foreach ($interests as $interest) {
                    $item = Items::find($interest->item_id);
                    if ($item) {
                        $item->interest_count = $interest->count; // Optional: attach count
                        $interestedItems->push($item);
                    }
                }
                $categoryIds = $interests->pluck('category_id')->unique()->toArray();
                $subCategoryIds = $interests->pluck('sub_category_id')->unique()->toArray();

                // Step 5: Get items where category matches but item not already in interest
                $categoryMatchItems = Items::whereIn('category_id', $categoryIds)
                    ->whereNotIn('id', $interestedItemIds)->where('is_approved', 1)->where('status', 1)
                    ->get();

                // Step 6: Get items where sub_category matches but item not already in interest or category matched
                $excludeIds = array_merge($interestedItemIds, $categoryMatchItems->pluck('id')->toArray());

                $subCategoryMatchItems = Items::whereIn('sub_category_id', $subCategoryIds)
                    ->whereNotIn('id', $excludeIds)->where('is_approved', 1)->where('status', 1)
                    ->get();

                // Final: Merge all, interested first, then category matched, then sub_category matched
                $data = $interestedItems->merge($categoryMatchItems)->merge($subCategoryMatchItems)->values();
                $query = $data->take($no_of_content)->toArray();
                for ($j = 0; $j < count($query); $j++) {

                    $query[$j]['portrait_img'] = $this->getImage($this->folder_item, $query[$j]['portrait_img']);
                    $query[$j]['landscape_img'] = $this->getImage($this->folder_item, $query[$j]['landscape_img']);

                    if ($query[$j]['video_type'] == 1) {
                        $query[$j]['url'] = $this->getImage($this->folder_item, $query[$j]['url']);
                    }

                    $query[$j]['category_name'] = $this->GetCategoryNameByIds($query[$j]['category_id']);
                    $query[$j]['sub_category_name'] = $this->GetSubCategoryNameByIds($query[$j]['sub_category_id']);
                    $query[$j]['brand_name'] = $this->GetBrandNameByIds($query[$j]['brand_id']);
                    $query[$j]['seller_name'] = $this->GetSellerNameByIds($query[$j]['seller_id']);
                    $query[$j]['tag_name'] = $this->GetTagNameByIds($query[$j]['tag_id']);
                    $query[$j]['tax_name'] = $this->GetTaxNameByIds($query[$j]['tax_id']);
                    $query[$j]['stock_name'] = $this->GetStockNameByIds($query[$j]['stock_status_id']);
                    $query[$j]['final_price'] = $query[$j]['price'];
                    $query[$j]['avg_rating'] = $this->avg_rating($query[$j]['id'], 1);
                    $query[$j]['total_reviews'] = $this->review_count($query[$j]['id'], 1);
                    $query[$j]['is_bookmark'] = $this->is_bookmark($customer_id, $query[$j]['id']);
                    $query[$j]['is_cart'] = $this->is_addtocart($customer_id, $query[$j]['id']);
                }

                return $query;
            }
        }
    }
    public function GetCategoryNameByIds($ids)
    {
        $Ids = $ids;
        $data = Category::select('id', 'name')->where('id', $Ids)->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data = $value['name'];
            }
            $IDs = $final_data;
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetSubCategoryNameByIds($ids)
    {
        $Ids = $ids;
        $data = Category::select('id', 'name')->where('id', $Ids)->get();
        if (count($data) > 0) {

            foreach ($data as  $value) {
                $final_data = $value['name'];
            }
            $IDs = $final_data;
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetBrandNameByIds($ids)
    {
        $Ids = $ids;
        $data = Brand::select('id', 'name')->where('id', $Ids)->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data = $value['name'];
            }
            $IDs = $final_data;
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetSellerNameByIds($ids)
    {
        $Ids = $ids;
        $data = Customer::select('id', 'full_name')->where('id', $Ids)->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data = $value['full_name'];
            }
            $IDs = $final_data;
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetTagNameByIds($ids)
    {
        $Ids = explode(',', $ids);

        $data = Hashtag::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(", ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetTaxNameByIds($ids)
    {
        $Ids = explode(',', $ids);

        $data = Taxes::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(", ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetStockNameByIds($ids)
    {
        $Ids = $ids;
        $data = StockStatus::select('id', 'name')->where('id', $Ids)->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data = $value['name'];
            }
            $IDs = $final_data;
            return $IDs;
        } else {
            return "";
        }
    }
    public function GetDeliveryInstructorByIds($ids)
    {
        $Ids = explode(',', $ids);

        $data = DeliveryInstructor::select('id', 'title', 'image')->whereIn('id', $Ids)->latest()->get();
        $final_data = [];

        if ($data->count() > 0) {
            foreach ($data as $value) {
                $final_data[] = [
                    'title' => $value['title'],
                    'image' => $this->getImage($this->folder_delivery_instructor, $value['image']), // relative path
                ];
            }
        }

        return $final_data; // returns array of arrays
    }
    public function GetDeliveryAssignmentInfo($group_order_id)
    {
        $assignment = DeliveryAssignment::where('group_order_id', $group_order_id)->with('deliveryboy')->first();

        if (!$assignment) {
            return [];
        }

        $deliveryBoy = $assignment->deliveryboy;

        $assignment['delivery_boy_user_name'] = $deliveryBoy->user_name ?? '';
        $assignment['delivery_boy_full_name'] = $deliveryBoy->full_name ?? '';
        $assignment['delivery_boy_number'] = ($deliveryBoy->country_code ?? '') . ' ' . ($deliveryBoy->mobile_number ?? '');
        $assignment['delivery_boy_image'] = $this->getImage($this->folder_delivery_boy, $deliveryBoy->image ?? '');
        $assignment['vehicle_name'] = $deliveryBoy->vehicle_name ?? '';
        $assignment['vehicle_number'] = $deliveryBoy->vehicle_number ?? '';

        unset($assignment->deliveryboy);

        return [$assignment];
    }
    public function GetInnerOrderItems($group_order_id)
    {
        $items = OrdersItems::where('group_order_id', $group_order_id)->with('item')->get();

        foreach ($items as  $value) {
            if ($value->item != null) {
                $value['item_name'] = $value->item['name'];
                $value['item_image'] = $this->getImage($this->folder_item, $value->item->portrait_img);
            } else {
                $value['item_name'] = "";
                $value['item_image'] = "";
            }
            $value['item_avg_rating'] = $value->item != null ? $this->avg_rating($value->item->id, 1) : '0.0';

            unset($value['item']);
        }

        return $items;
    }
    public function SellerStoreInfo($seller_id)
    {
        $get = SellerRequest::where('customer_id', $seller_id)->first();
        if (!$get) {
            return [
                'store_name' => '',
                'store_logo' => $this->getImage($this->folder_customer, ''),
            ];
        }

        $array = [
            'store_name' => $get['store_name'] ?? '',
            'store_logo' => $this->getImage($this->folder_customer, $get['store_logo'] ?? ''),
        ];

        return $array;
    }
    public function GetOrdersDetails($Orders)
    {
        $seller = Customer::find($Orders['seller_id']);
        $customer = Customer::find($Orders['customer_id']);
        $customer_add = CustomerAddress::find($Orders['customer_address_id']);
        $list = [
            'email' => $seller?->email ?? '',
            'seller_name' => $seller?->full_name ?? '',
            'customer_name' => $customer?->full_name ?? '',
            'customer_email' => $customer?->email ?? '',
            'customer_phone' => ($customer?->country_code ?? '') . ($customer?->mobile_number ?? ''),
            'total_amount' => $Orders['total_payable_amount'],
            'payment_method' => $Orders['payment_method'],
            'order_no' => $Orders['group_order_id'],
            'order_date' => $Orders['created_at'],
            'address_info' => $customer_add,
        ];

        return $list;
    }
    public function MaskEmail()
    {
        $maskEmail = function ($email) {
            if (!strpos($email, '@')) {
                return $email;
            }

            $parts = explode('@', $email);
            $name = $parts[0];
            $domain = $parts[1] ?? '';

            $length = strlen($name);
            $masked = $length <= 2
                ? str_repeat('*', $length)
                : substr($name, 0, 1) . str_repeat('*', $length - 2) . substr($name, -1);

            return $masked . '@' . $domain;
        };

        return $maskEmail;
    }
    public function MaskMobileNumber()
    {
        $maskMobile = function ($mobile) {
            return strlen($mobile) >= 4
                ? substr($mobile, 0, 2) . str_repeat('*', strlen($mobile) - 4) . substr($mobile, -2)
                : str_repeat('*', strlen($mobile));
        };

        return $maskMobile;
    }
    public function CommonItemsDetails($data, $customer_id)
    {
        for ($i = 0; $i < count($data); $i++) {

            $data[$i]['portrait_img'] = $this->getImage($this->folder_item, $data[$i]->portrait_img);
            $data[$i]['landscape_img'] = $this->getImage($this->folder_item, $data[$i]->landscape_img);
            if ($data[$i]['video_type'] == 1) {
                $data[$i]['url'] = $this->getImage($this->folder_item, $data[$i]->url);
            }
            $data[$i]['category_name'] = $this->GetCategoryNameByIds($data[$i]['category_id']);
            $data[$i]['sub_category_name'] = $this->GetSubCategoryNameByIds($data[$i]['sub_category_id']);
            $data[$i]['brand_name'] = $this->GetBrandNameByIds($data[$i]['brand_id']);
            $data[$i]['seller_name'] = $this->GetSellerNameByIds($data[$i]['seller_id']);
            $data[$i]['tag_name'] = $this->GetTagNameByIds($data[$i]['tag_id']);
            $data[$i]['tax_name'] = $this->GetTaxNameByIds($data[$i]['tax_id']);
            $data[$i]['stock_name'] = $this->GetStockNameByIds($data[$i]['stock_status_id']);
            $data[$i]['avg_rating'] = $this->avg_rating($data[$i]['id'], 1);
            $data[$i]['review_count'] = $this->review_count($data[$i]['id'], 1);
            $data[$i]['is_bookmark'] = $this->is_bookmark($customer_id, $data[$i]['id']);
            $data[$i]['is_cart'] = $this->is_addtocart($customer_id, $data[$i]['id']);
        }
        return $data;
    }
    public function GetItemFullDetails($item, $customer_id)
    {
        $item['final_price'] = $item['price'];

        $this->imageNameToUrl([$item], 'portrait_img', $this->folder_item);
        $this->imageNameToUrl([$item], 'landscape_img', $this->folder_item);
        if ($item['video_type'] == 1) {
            $this->imageNameToUrl([$item], 'url', $this->folder_item);
        }

        $item['category_name'] = $this->GetCategoryNameByIds($item['category_id']);
        $item['sub_category_name'] = $this->GetSubCategoryNameByIds($item['sub_category_id']);
        $item['brand_name'] = $this->GetBrandNameByIds($item['brand_id']);
        $item['seller_name'] = $this->GetSellerNameByIds($item['seller_id']);
        $item['tag_name'] = $this->GetTagNameByIds($item['tag_id']);
        $item['tax_name'] = $this->GetTaxNameByIds($item['tax_id']);
        $item['stock_name'] = $this->GetStockNameByIds($item['stock_status_id']);
        $item['avg_rating'] = $this->avg_rating($item['id'], 1);
        $item['total_reviews'] = $this->review_count($item['id'], 1);
        $item['is_bookmark'] = $this->is_bookmark($customer_id, $item['id']);
        $item['is_cart'] = $this->is_addtocart($customer_id, $item['id']);

        $itemAttributes = [];

        $attributes = ItemsAttributes::where('item_id', $item['id'])->get();

        foreach ($attributes as $attr) {

            $attribute = Attributes::where('id', $attr->attributes_id)->first();
            if (!$attribute) continue;

            $attribute->icon = $this->getImage($this->folder_attributes, $attribute->icon);

            // Text / Numeric
            if (in_array($attribute->field_type, [1, 2])) {

                $attribute->items_selected_value = $attr->option;
                $attribute->attributes_value = [];
            }
            // File Upload
            elseif ($attribute->field_type == 6) {

                if (!empty($attr->option) && $attr->option != "no_img.png") {
                    $attribute->items_selected_value = $this->getImage($this->folder_item, $attr->option);
                    $attribute->attributes_value = [];
                } else {
                    continue;
                }
            }
            // Dropdown / Radio / Checkbox
            elseif (in_array($attribute->field_type, [3, 4, 5])) {

                $options = AttributesOption::where('attributes_id', $attribute->id)->get();
                $selectedValues = explode(',', $attr->option);

                foreach ($options as $opt) {
                    $opt->selected_value = in_array($opt->option, $selectedValues)
                        ? $opt->option
                        : "";
                }

                $attribute->items_selected_value = $attr->option;
                $attribute->attributes_value = $options;
            }

            $itemAttributes[] = $attribute;
        }

        $item['item_attribute'] = $itemAttributes;

        /* ================= MULTI IMAGES ================= */
        $images = ItemsImages::where('item_id', $item['id'])->get();
        foreach ($images as $img) {
            $img['image'] = $this->getImage($this->folder_item, $img['image']);
        }
        $item['item_images'] = $images;

        /* ================= FAQ ================= */
        $item['item_faq'] = ItemsFaq::where('item_id', $item['id'])->get();

        return $item;
    }
    public function CommonOrderDetails($data)
    {
        foreach ($data as  $value) {

            $createdAt = $value->created_at;
            $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';
            $value['order_date_time'] = $orderDateTime;

            if ($value->customer != null) {
                $value['customer_user_name'] = $value->customer['user_name'];
                $value['customer_name'] = $value->customer['full_name'];
                $value['customer_mobile_number'] = ($value->customer->country_code ?? '') . ' ' . ($value->customer->mobile_number ?? '');
                $value['customer_image'] = $this->getImage($this->folder_customer, $value->customer->image);
            } else {
                $value['customer_user_name'] = "";
                $value['customer_name'] = "";
                $value['customer_mobile_number'] = "";
                $value['customer_image'] = "";
            }

            if ($value->seller != null) {

                $value['seller_user_name'] = $value->seller['user_name'];
                $value['seller_name'] = $value->seller['full_name'];
                $value['seller_image'] = $this->getImage($this->folder_customer, $value->seller->image);
            } else {
                $value['seller_user_name'] = "";
                $value['seller_name'] = "";
                $value['seller_image'] = "";
            }

            $value['items'] = $this->GetInnerOrderItems($value['group_order_id']);
            $value['address'] = [];
            if ($value['customer_address'] != null) {
                $value['address'] = [$value['customer_address']];
            }
            $value['delivery_instructor'] = $this->GetDeliveryInstructorByIds($value['delivery_instructor_id']);
            $value['delivery_assignment'] = $this->GetDeliveryAssignmentInfo($value['group_order_id']);
            unset($value->customer, $value->customer_address, $value->seller, $value->item);
        }
    }
    public function DeleteOlderReceipt()
    {
        $files = Storage::allFiles('public/invoice');

        foreach ($files as $file) {
            Storage::delete($file);
        }
    }
    public function OrderTrackingAdd($group_order_id, $status)
    {
        OrdersTracking::updateOrCreate(
            [
                'group_order_id' => $group_order_id,
                'status' => $status
            ],

        );
    }
    public function CreateSettlement($group_order_id)
    {
        if (Settelment::where('group_order_id', $group_order_id)->exists()) {
            return true;
        }

        $assignment = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
        $order = Orders::where('group_order_id', $group_order_id)->first();

        if (!$assignment || !$order) return false;

        // Get last settlement number
        $lastSettlement = Settelment::orderBy('id', 'desc')->first();

        $nextNumber = $lastSettlement ? intval(str_replace('SET-', '', $lastSettlement->settelment_no)) + 1 : 1;

        $settlementNo = 'SET-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // DELIVERY BOY SETTLEMENT
        $deliveryCommission = $assignment->earning ?? 0;
        if ($deliveryCommission > 0) {
            Settelment::create([
                'settelment_no'      => $settlementNo,
                'type'               => 2,
                'group_order_id'     => $group_order_id,
                'type_id'            => $assignment->delivery_boy_id,
                'commission_amount'  => $deliveryCommission,
                'adjustment_amount'  => 0,
                'settlement_amount'  => $deliveryCommission,
                'status'             => 1
            ]);
        }

        // SELLER SETTLEMENT
        $sellerCommission = $order->seller_commission ?? 0;
        if ($sellerCommission > 0) {

            // Generate new settlement number for seller
            $nextNumber++;
            $sellerSettlementNo = 'SET-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            Settelment::create([
                'settelment_no'      => $sellerSettlementNo,
                'type'               => 1,
                'group_order_id'     => $group_order_id,
                'type_id'            => $order->seller_id,
                'commission_amount'  => $sellerCommission,
                'adjustment_amount'  => 0,
                'settlement_amount'  => $sellerCommission,
                'status'             => 1
            ]);
        }

        return true;
    }
    // public function CreateSettlement($group_order_id)
    // {
    //     if (Settelment::where('group_order_id', $group_order_id)->exists()) {
    //         return true;
    //     }

    //     $assignment = DeliveryAssignment::where('group_order_id', $group_order_id)->first();
    //     if (!$assignment) return false;

    //     $commission = $assignment->earning;

    //     $lastSettlement = Settelment::orderBy('id', 'desc')->first();

    //     $nextNumber = $lastSettlement
    //         ? intval(str_replace('SET-', '', $lastSettlement->settlement_no)) + 1
    //         : 1;

    //     $settlementNo = 'SET-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    //     Settelment::create([
    //         'settelment_no'      => $settlementNo,
    //         'type'              => 2,
    //         'group_order_id'    => $group_order_id,
    //         'type_id'   => $assignment->delivery_boy_id,
    //         'commission_amount' => $commission,
    //         'adjustment_amount' => 0,
    //         'settlement_amount' => $commission,
    //         'status'            => 1
    //     ]);

    //     return true;
    // }
    public function OrderDeliveryDate($group_order_id)
    {

        $ordersinfo = OrdersTracking::where('group_order_id', $group_order_id)->get();

        $deliveryDetails = [];

        foreach ($ordersinfo as $value) {
            switch ($value->status) {
                case 1:
                    $deliveryStatus = 'Pending';
                    break;
                case 2:
                    $deliveryStatus = 'Confirmed';
                    break;
                case 3:
                    $deliveryStatus = 'Shipped';
                    break;
                case 4:
                    $deliveryStatus = 'Out for delivery';
                    break;
                case 5:
                    $deliveryStatus = 'Delivered';
                    break;
                default:
                    $deliveryStatus = '';
                    break;
            }

            $deliveryDetails[] = [
                'status' => $deliveryStatus,
                'date' => $value->updated_at,
            ];
        }
        return $deliveryDetails;
    }
    public function GetNewOrdersDetails($group_order_id)
    {
        $orders = Orders::with('item')->where('group_order_id', $group_order_id)->get();
        $orderItems = [];

        foreach ($orders as $order) {
            $orderItems[] = [
                'order_number' => $order->order_number,
                'item_id'      => $order->item_id,
                'item_title'   => $order->item ? $order->item->name : '', // assuming relation
                'quantity'     => $order->order_quantity,
                'total_price'  => $order->item_amount,

            ];
        }

        $responseData = [
            'items' => $orderItems
        ];
        return $responseData;
    }
    public function DeliveryTripTiming($pickupTime, $estimatedTime)
    {
        $start = $pickupTime ? strtotime($pickupTime) : false;
        $end = $estimatedTime ? strtotime($estimatedTime) : false;
        if (!$start || !$end || $end < $start) {
            return '';
        }

        $minutes = (int) round(($end - $start) / 60);
        return $minutes . ' min';
    }
    public function GetOrdersData($value)
    {

        $groupOrders = $value->group_order;
        $deliveryBoy = $value->deliveryboy;
        if (!$groupOrders) {
            $value['distance_km'] = '';
            $value['duration'] = $this->DeliveryTripTiming($value->pickup_time ?? null, $value->estimated_delivery_time ?? null);
            $value['latitude'] = '';
            $value['longitude'] = '';
            unset($value->group_order, $value->deliveryboy);
            return $value;
        }
        $createdAt = $groupOrders->created_at;
        $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';
        $customer = Customer::where('id', $groupOrders['customer_id'])->first();

        $value['item_total'] = $groupOrders['item_total'];
        $value['delivery_charges'] = $groupOrders['delivery_charges'];
        $value['handling_charges'] = $groupOrders['handling_charges'];
        $value['donation_amount'] = $groupOrders['donation_amount'];
        $value['discount_amount'] = $groupOrders['discount_amount'];
        $value['total_payable_amount'] = $groupOrders['total_payable_amount'];
        $value['payment_method'] = $groupOrders['payment_method'];
        $value['payment_status'] = $groupOrders['payment_status'];
        $value['order_date_time'] = $orderDateTime;
        $value['delivery_boy_user_name'] = $deliveryBoy->user_name ?? '';
        $value['delivery_boy_full_name'] = $deliveryBoy->full_name ?? '';
        $value['delivery_boy_number'] = ($deliveryBoy->country_code ?? '') . ' ' . ($deliveryBoy->mobile_number ?? '');
        $value['delivery_boy_image'] = $this->getImage($this->folder_delivery_boy, $deliveryBoy->image ?? '');
        $value['vehicle_name'] = $deliveryBoy->vehicle_name ?? '';
        $value['vehicle_number'] = $deliveryBoy->vehicle_number ?? '';

        if ($customer != null) {
            $value['customer_user_name'] = $customer['user_name'];
            $value['customer_name'] = $customer['full_name'];
            $value['customer_mobile_number'] = optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '';
            $value['customer_image'] = $this->getImage($this->folder_customer, optional($customer)->image ?? '');
        } else {
            $value['customer_user_name'] = "";
            $value['customer_name'] = "";
            $value['customer_image'] = "";
            $value['customer_mobile_number'] = "";
        }

        $value['items'] = $this->GetInnerOrderItems($groupOrders['group_order_id']);

        $address  = CustomerAddress::find($groupOrders->customer_address_id);
        $value['address'] = [];
        $value['latitude'] = $address?->latitude ?? '';
        $value['longitude'] = $address?->longitude ?? '';
        if ($address != null) {
            $value['address'] = [$address];
        }
        $value['distance_km'] = '';
        $value['duration'] = $this->DeliveryTripTiming($value->pickup_time ?? null, $value->estimated_delivery_time ?? null);

        $value['delivery_instructor'] = $this->GetDeliveryInstructorByIds($groupOrders['delivery_instructor_id']);

        unset($value->group_order, $value->deliveryboy);
        return $value;

        // $deliveryDetails = $this->GetAssignDeliveryDetails($value->group_order_id);
        // $items = [];
        // $customerData = [];

        // $itemData = OrdersItems::where('group_order_id', $groupOrders->group_order_id)->with('item')->get();
        // foreach ($itemData as $row) {
        //     $items[] = [
        //         'id'    => $row->id,
        //         'group_order_id' => $row->group_order_id,
        //         'order_number' => $row->order_number,
        //         'customer_id' => $row->customer_id,
        //         'item_id'       => $row->item_id,
        //         'seller_id' => $row->seller_id,
        //         'variation' => $row->variation ?? '',
        //         'item_price'         => $row->item_price,
        //         'quantity'      => $row->order_quantity,
        //         'total_item_price'   => $row->order_quantity_amount,
        //         'item_name'     => optional($row->item)->name ?? '',
        //         'item_image'    => $this->getImage($this->folder_item, $row->item->portrait_img),
        //     ];
        // }

        // $customer = Customer::find($groupOrders->customer_id);
        // $address  = CustomerAddress::find($groupOrders->customer_address_id);

        // $customerData = [
        //     'user_name'      => optional($customer)->user_name ?? '',
        //     'full_name'      => optional($customer)->full_name ?? '',
        //     'mobile_number' => optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '',
        //     'image' => $this->getImage($this->folder_customer, optional($customer)->image ?? ''),
        //     'address_id'     => $address['id'],
        //     'address_type'           => optional($address)->type ?? '',
        //     'address'        => optional($address)->address ?? '',
        //     'latitude'        => optional($address)->latitude ?? '',
        //     'longitude'        => optional($address)->longitude ?? '',
        //     'area'           => optional($address)->area ?? '',
        //     'city'           => optional($address)->city ?? '',
        //     'state'          => optional($address)->state ?? '',
        //     'country'        => optional($address)->country ?? '',
        //     'pincode'        => optional($address)->pincode ?? '',
        // ];

        // $createdAt = $groupOrders->created_at;
        // $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';
        // return [
        //     'id'                   => $value->id ?? 0,
        //     'group_order_id'       => $value->group_order_id ?? '',
        //     'order_date_time'      => $orderDateTime,
        //     'delivery_status'      => $deliveryDetails['status'],
        //     'items'                => $items,
        //     'customer'             => [$customerData],
        //     'delivery_instructor' => $this->GetDeliveryInstructorByIds($groupOrders['delivery_instructor_id']),
        //     'delivery_assignment' => $this->GetDeliveryAssignmentInfo($groupOrders['group_order_id']),

        // ];
    }
    public function GetGroupOrdersDetails($value)
    {
        $groupOrders = $value->group_order;
        $deliveryBoy = $value->deliveryboy;
        if (!$groupOrders) {
            $value['distance_km'] = '';
            $value['duration'] = $this->DeliveryTripTiming($value->pickup_time ?? null, $value->estimated_delivery_time ?? null);
            $value['latitude'] = '';
            $value['longitude'] = '';
            unset($value->group_order, $value->deliveryboy);
            return $value;
        }
        $createdAt = $groupOrders->created_at;
        $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';
        $customer = Customer::where('id', $groupOrders['customer_id'])->first();

        $value['item_total'] = $groupOrders['item_total'];
        $value['delivery_charges'] = $groupOrders['delivery_charges'];
        $value['handling_charges'] = $groupOrders['handling_charges'];
        $value['donation_amount'] = $groupOrders['donation_amount'];
        $value['discount_amount'] = $groupOrders['discount_amount'];
        $value['total_payable_amount'] = $groupOrders['total_payable_amount'];
        $value['payment_method'] = $groupOrders['payment_method'];
        $value['payment_status'] = $groupOrders['payment_status'];
        $value['order_date_time'] = $orderDateTime;
        $value['delivery_boy_user_name'] = $deliveryBoy->user_name ?? '';
        $value['delivery_boy_full_name'] = $deliveryBoy->full_name ?? '';
        $value['delivery_boy_number'] = ($deliveryBoy->country_code ?? '') . ' ' . ($deliveryBoy->mobile_number ?? '');
        $value['delivery_boy_image'] = $this->getImage($this->folder_delivery_boy, $deliveryBoy->image ?? '');
        $value['vehicle_name'] = $deliveryBoy->vehicle_name ?? '';
        $value['vehicle_number'] = $deliveryBoy->vehicle_number ?? '';

        if ($customer != null) {
            $value['customer_user_name'] = $customer['user_name'];
            $value['customer_name'] = $customer['full_name'];
            $value['customer_mobile_number'] = optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '';
            $value['customer_image'] = $this->getImage($this->folder_customer, optional($customer)->image ?? '');
        } else {
            $value['customer_user_name'] = "";
            $value['customer_name'] = "";
            $value['customer_image'] = "";
            $value['customer_mobile_number'] = "";
        }

        $value['items'] = $this->GetInnerOrderItems($groupOrders['group_order_id']);

        $address  = CustomerAddress::find($groupOrders->customer_address_id);
        $value['address'] = [];
        $value['latitude'] = $address?->latitude ?? '';
        $value['longitude'] = $address?->longitude ?? '';
        if ($address != null) {
            $value['address'] = [$address];
        }
        $value['distance_km'] = '';
        $value['duration'] = $this->DeliveryTripTiming($value->pickup_time ?? null, $value->estimated_delivery_time ?? null);

        $value['delivery_instructor'] = $this->GetDeliveryInstructorByIds($groupOrders['delivery_instructor_id']);

        unset($value->group_order, $value->deliveryboy);
        return $value;

        // $deliveryDetails = $this->GetAssignDeliveryDetails($orders->group_order_id);
        // $customerData = [];
        // $items = [];

        // $itemData = OrdersItems::where('group_order_id', $orders->group_order_id)->with('item')->get();

        // foreach ($itemData as $row) {
        //     $items[] = [
        //         'id'    => $row->id,
        //         'group_order_id' => $row->group_order_id,
        //         'order_number' => $row->order_number,
        //         'customer_id' => $row->customer_id,
        //         'item_id'       => $row->item_id,
        //         'seller_id' => $row->seller_id,
        //         'variation' => $row->variation ?? '',
        //         'item_price'         => $row->item_price,
        //         'quantity'      => $row->order_quantity,
        //         'total_item_price'   => $row->order_quantity_amount,
        //         'item_name'     => optional($row->item)->name ?? '',
        //         'item_image'    => $this->getImage($this->folder_item, $row->item->portrait_img),
        //     ];
        // }

        // $customer = Customer::find($orders->customer_id);
        // $address  = CustomerAddress::find($orders->customer_address_id);

        // $customerData = [
        //     'user_name'      => optional($customer)->user_name ?? '',
        //     'full_name'      => optional($customer)->full_name ?? '',
        //     'mobile_number' => optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '',
        //     'image' => $this->getImage($this->folder_customer, optional($customer)->image ?? ''),
        //     'address_id'     => $address['id'],
        //     'address_type'           => optional($address)->type ?? '',
        //     'latitude'        => optional($address)->latitude ?? '',
        //     'longitude'        => optional($address)->longitude ?? '',
        //     'address'        => optional($address)->address ?? '',
        //     'area'           => optional($address)->area ?? '',
        //     'city'           => optional($address)->city ?? '',
        //     'state'          => optional($address)->state ?? '',
        //     'country'        => optional($address)->country ?? '',
        //     'pincode'        => optional($address)->pincode ?? '',
        // ];


        // $createdAt = $orders->created_at;
        // $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';

        // return [
        //     'id'                   => $orders->id ?? 0,
        //     'group_order_id'       => $orders->group_order_id ?? '',
        //     'customer_id'          => $orders->customer_id ?? 0,
        //     'seller_id'             => $orders->seller_id ?? 0,
        //     'sub_total'         => $orders->item_total ?? 0,
        //     'delivery_charges'  => $orders->delivery_charges ?? 0,
        //     'handling_charges'  => $orders->handling_charges ?? 0,
        //     'donation_amount'  => $orders->donation_amount ?? 0,
        //     'discount_amount'  => $orders->discount_amount ?? 0,
        //     'total_payable_amount' => $orders->total_payable_amount ?? 0,
        //     'promo_codes_id'        => $orders->promo_codes_id,
        //     'customer_address_id'  => $orders->customer_address_id,
        //     'delivery_instructor_id' => $orders->delivery_instructor_id,
        //     'payment_method'       => $orders->payment_method,
        //     'payment_status'       => $orders->payment_status,
        //     'is_assigned'          => $orders->is_assigned ?? 0,
        //     'order_notes'          => $orders->order_notes ?? '',
        //     'seller_commission'    => $orders->seller_commission,
        //     'delivery_boy_commission' => $orders->delivery_boy_commission,
        //     'delivery_otp' => $orders->delivery_otp,
        //     'delivery_status'      => $deliveryDetails['status'],
        //     'order_date_time'      => $orderDateTime,
        //     'items'                => $items,
        //     'customer'             => [$customerData],
        //     'delivery_instructor' => $this->GetDeliveryInstructorByIds($orders['delivery_instructor_id']),
        //     'delivery_assignment' => $this->GetDeliveryAssignmentInfo($orders['group_order_id']),
        // ];
    }
    public function GetSellerOrdersData($orders)
    {

        $createdAt = $orders->created_at;
        $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';

        $orders['order_date_time'] = $orderDateTime;

        if ($orders->customer != null) {
            $orders['customer_user_name'] = $orders->customer['user_name'];
            $orders['customer_name'] = $orders->customer['full_name'];
            $orders['customer_mobile_number'] = ($orders->customer->country_code ?? '') . ' ' . ($orders->customer->mobile_number ?? '');
            $orders['customer_image'] = $this->getImage($this->folder_customer, $orders->customer->image);
        } else {
            $orders['customer_user_name'] = "";
            $orders['customer_name'] = "";
            $orders['customer_mobile_number'] = "";
            $orders['customer_image'] = "";
        }

        if ($orders->seller != null) {

            $orders['seller_user_name'] = $orders->seller['user_name'];
            $orders['seller_name'] = $orders->seller['full_name'];
            $orders['seller_image'] = $this->getImage($this->folder_customer, $orders->seller->image);
        } else {
            $orders['seller_user_name'] = "";
            $orders['seller_name'] = "";
            $orders['seller_image'] = "";
        }

        $orders['items'] = $this->GetInnerOrderItems($orders['group_order_id']);

        $orders['address'] = [];
        if ($orders['customer_address'] != null) {
            $orders['address'] = [$orders['customer_address']];
        }

        $orders['delivery_instructor'] = $this->GetDeliveryInstructorByIds($orders['delivery_instructor_id']);
        $orders['delivery_assignment'] = $this->GetDeliveryAssignmentInfo($orders['group_order_id']);

        unset($orders->seller, $orders->customer, $orders->customer_address);

        return $orders;

        // $items = [];
        // $customerData = [];

        // // $itemData = OrdersItems::where('group_order_id', $orders->group_order_id)->with('item')->get();
        // // foreach ($itemData as $row) {
        // //     $items[] = [
        // //         'id'    => $row->id,
        // //         'group_order_id' => $row->group_order_id,
        // //         'order_number' => $row->order_number,
        // //         'customer_id' => $row->customer_id,
        // //         'item_id'       => $row->item_id,
        // //         'seller_id' => $row->seller_id,
        // //         'variation' => $row->variation ?? '',
        // //         'item_price'         => $row->item_price,
        // //         'quantity'      => $row->order_quantity,
        // //         'total_item_price'   => $row->order_quantity_amount,
        // //         'item_name'     => optional($row->item)->name ?? '',
        // //         'item_image'    => $this->getImage($this->folder_item, $row->item->portrait_img),
        // //     ];
        // // }

        // $customer = Customer::find($orders->customer_id);
        // $address  = CustomerAddress::find($orders->customer_address_id);
        // $customerData = [
        //     'user_name'      => optional($customer)->user_name ?? '',
        //     'full_name'      => optional($customer)->full_name ?? '',
        //     'mobile_number' => optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '',
        //     'image' => $this->getImage($this->folder_customer, optional($customer)->image ?? ''),
        //     'address_id'     => $address['id'],
        //     'address_type'           => optional($address)->type ?? '',
        //     'address'        => optional($address)->address ?? '',
        //     'latitude'        => optional($address)->latitude ?? '',
        //     'longitude'        => optional($address)->longitude ?? '',
        //     'area'           => optional($address)->area ?? '',
        //     'city'           => optional($address)->city ?? '',
        //     'state'          => optional($address)->state ?? '',
        //     'country'        => optional($address)->country ?? '',
        //     'pincode'        => optional($address)->pincode ?? '',
        // ];

        // $orders['items'] = $items;
        // $orders['customer'] = [$customerData];

        // return [
        //     'id'                   => $orders->id ?? 0,
        //     'group_order_id'       => $orders->group_order_id ?? '',
        //     'customer_id'          => $orders->customer_id ?? 0,
        //     'seller_id'             => $orders->seller_id ?? 0,
        //     'item_total'         => $orders->item_total ?? 0,
        //     'delivery_charges'  => $orders->delivery_charges ?? 0,
        //     'handling_charges'  => $orders->handling_charges ?? 0,
        //     'donation_amount'  => $orders->donation_amount ?? 0,
        //     'discount_amount'  => $orders->discount_amount ?? 0,
        //     'total_payable_amount' => $orders->total_payable_amount ?? 0,
        //     'promo_codes_id'        => $orders->promo_codes_id,
        //     'customer_address_id'  => $orders->customer_address_id,
        //     'delivery_instructor_id' => $orders->delivery_instructor_id,
        //     'payment_method'       => $orders->payment_method,
        //     'payment_status'       => $orders->payment_status,
        //     'is_assigned'          => $orders->is_assigned ?? 0,
        //     'order_notes'          => $orders->order_notes ?? '',
        //     'seller_commission'    => $orders->seller_commission,
        //     'delivery_boy_commission' => $orders->delivery_boy_commission,
        //     'delivery_otp' => $orders->delivery_otp,
        //     'order_status'      => $orders['order_status'],
        //     'order_date_time'      => $orderDateTime,
        //     'updated_at'=>$orders->updated_at,
        //     'created_at'=>$orders->created_at,
        //     'items'                => $items,
        //     'customer'             => [$customerData],
        //     'delivery_instructor' => $this->GetDeliveryInstructorByIds($orders['delivery_instructor_id']),
        //     'delivery_assignment' => $this->GetDeliveryAssignmentInfo($orders['group_order_id']),
        // ];
    }
    public function GetSellerGroupOrdersDetails($orders)
    {
        $createdAt = $orders->created_at;
        $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';
        $orders['order_date_time'] = $orderDateTime;

        if ($orders->customer != null) {
            $orders['customer_user_name'] = $orders->customer['user_name'];
            $orders['customer_name'] = $orders->customer['full_name'];
            $orders['customer_mobile_number'] = ($orders->customer->country_code ?? '') . ' ' . ($orders->customer->mobile_number ?? '');
            $orders['customer_image'] = $this->getImage($this->folder_customer, $orders->customer->image);
        } else {
            $orders['customer_user_name'] = "";
            $orders['customer_name'] = "";
            $orders['customer_mobile_number'] = "";
            $orders['customer_image'] = "";
        }

        if ($orders->seller != null) {

            $orders['seller_user_name'] = $orders->seller['user_name'];
            $orders['seller_name'] = $orders->seller['full_name'];
            $orders['seller_image'] = $this->getImage($this->folder_customer, $orders->seller->image);
        } else {
            $orders['seller_user_name'] = "";
            $orders['seller_name'] = "";
            $orders['seller_image'] = "";
        }

        $orders['items'] = $this->GetInnerOrderItems($orders['group_order_id']);

        $orders['address'] = [];
        if ($orders['customer_address'] != null) {
            $orders['address'] = [$orders['customer_address']];
        }

        $orders['delivery_instructor'] = $this->GetDeliveryInstructorByIds($orders['delivery_instructor_id']);
        $orders['delivery_assignment'] = $this->GetDeliveryAssignmentInfo($orders['group_order_id']);

        unset($orders->seller, $orders->customer, $orders->customer_address);

        return $orders;

        // $items = [];
        // $customerData = [];

        // $itemData = OrdersItems::where('group_order_id', $orders->group_order_id)->with('item')->get();

        // foreach ($itemData as $row) {
        //     $items[] = [
        //         'id'    => $row->id,
        //         'group_order_id' => $row->group_order_id,
        //         'order_number' => $row->order_number,
        //         'customer_id' => $row->customer_id,
        //         'item_id'       => $row->item_id,
        //         'seller_id' => $row->seller_id,
        //         'variation' => $row->variation ?? '',
        //         'item_price'         => $row->item_price,
        //         'quantity'      => $row->order_quantity,
        //         'total_item_price'   => $row->order_quantity_amount,
        //         'item_name'     => optional($row->item)->name ?? '',
        //         'item_image'    => $this->getImage($this->folder_item, $row->item->portrait_img),
        //     ];
        // }

        // $customer = Customer::find($orders->customer_id);
        // $address  = CustomerAddress::find($orders->customer_address_id);

        // $customerData = [
        //     'user_name'      => optional($customer)->user_name ?? '',
        //     'full_name'      => optional($customer)->full_name ?? '',
        //     'mobile_number' => optional($customer)->country_code ? optional($customer)->country_code . ' ' . optional($customer)->mobile_number : '',
        //     'image' => $this->getImage($this->folder_customer, optional($customer)->image ?? ''),
        //     'address_id'     => $orders->customer_address_id,
        //     'address_type'           => optional($address)->type ?? '',
        //     'address'        => optional($address)->address ?? '',
        //     'latitude'        => optional($address)->latitude ?? '',
        //     'longitude'        => optional($address)->longitude ?? '',
        //     'area'           => optional($address)->area ?? '',
        //     'city'           => optional($address)->city ?? '',
        //     'state'          => optional($address)->state ?? '',
        //     'country'        => optional($address)->country ?? '',
        //     'pincode'        => optional($address)->pincode ?? '',
        // ];

        // $createdAt = $orders->created_at;
        // $orderDateTime = $createdAt ? date('d M Y h:i A', strtotime($createdAt)) : '';

        // return [
        //     'id'                   => $orders->id,
        //     'group_order_id'       => $orders->group_order_id ?? '',
        //     'customer_id'          => $orders->customer_id ?? 0,
        //     'seller_id'             => $orders->seller_id ?? 0,
        //     'sub_total' => $orders->item_total ?? 0,
        //     'delivery_charges'  => $orders->delivery_charges ?? 0,
        //     'handling_charges'  => $orders->handling_charges ?? 0,
        //     'donation_amount'  => $orders->donation_amount ?? 0,
        //     'discount_amount'  => $orders->discount_amount ?? 0,
        //     'total_payable_amount' => $orders->total_payable_amount ?? 0,
        //     'promo_codes_id'        => $orders->promo_codes_id,
        //     'customer_address_id'  => $orders->customer_address_id,
        //     'delivery_instructor_id' => $orders->delivery_instructor_id,
        //     'payment_method'       => $orders->payment_method,
        //     'payment_status'       => $orders->payment_status,
        //     'is_assigned'          => $orders->is_assigned ?? 0,
        //     'order_notes'          => $orders->order_notes ?? '',
        //     'seller_commission'    => $orders->seller_commission,
        //     'delivery_boy_commission' => $orders->delivery_boy_commission,
        //     'delivery_otp' => $orders->delivery_otp,
        //     'order_status'      => $orders['order_status'],
        //     'order_date_time'      => $orderDateTime,
        //     'items'                => $items,
        //     'customer'             => [$customerData],
        //     'delivery_instructor' => $this->GetDeliveryInstructorByIds($orders['delivery_instructor_id']),
        //     'delivery_assignment' => $this->GetDeliveryAssignmentInfo($orders['group_order_id']),
        // ];
    }
    public function GetDeliveryStatus($status)
    {
        if ($status == 1) {
            $statusName = "Assigned";
        } else if ($status == 2) {
            $statusName = "Accepted";
        } else if ($status == 3) {
            $statusName = "Rejected";
        } else if ($status == 4) {
            $statusName = "Picked Up";
        } else if ($status == 5) {
            $statusName = "On the way";
        } else if ($status == 6) {
            $statusName = "Delivered";
        } else {
            $statusName = "";
        }

        return  $statusName;
    }
    public function GetAssignDeliveryDetails($group_order_id)
    {
        $data = DeliveryAssignment::where('group_order_id', $group_order_id)->first();

        return $data;
    }
    public function DeliveryBoyEarningDetails($delivery_boy_id)
    {
        $today = date('Y-m-d');
        $settlementDays = Setting_Data()['settelment_payout_day'] ?? 0;

        // Completed Orders
        $completeOrders = DeliveryAssignment::where('delivery_boy_id', $delivery_boy_id)->where('status', 4)->count();

        // Total Earning
        $totalEarning = Settelment::where('type', 2)->where('type_id', $delivery_boy_id)->where('status', 2)->sum('commission_amount');

        // Total Settlement (Paid)
        $totalSettlement = Settelment::where('type', 2)->where('type_id', $delivery_boy_id)->where('status', 2)->sum('settlement_amount');

        // Today Earning
        $todayEarning = Settelment::where('type', 2)->where('type_id', $delivery_boy_id)->where('status', 2)->whereDate('created_at', $today)->sum('commission_amount');

        // Pending Settlement
        $pendingSettlement = Settelment::where('type', 2)->where('type_id', $delivery_boy_id)->where('status', 1)->sum('settlement_amount');

        // Next Payout Date
        $nextPayoutDate = '-';

        if ($pendingSettlement > 0) {

            $oldestPending = Settelment::where('type', 2)->where('type_id', $delivery_boy_id)
                ->where('status', 1)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestPending) {
                $nextPayoutDate = date(
                    'd M, Y',
                    strtotime($oldestPending->created_at . " +$settlementDays days")
                );
            }
        }

        return [
            'complete_orders'     => $completeOrders,
            'total_earning'       => $totalEarning,
            'today_earning'       => $todayEarning,
            'total_settlement'    => $totalSettlement,
            'pending_settlement'  => $pendingSettlement,
            'next_payout_date'    => $nextPayoutDate,
        ];
    }
    public function generateWithdrawalNumber()
    {
        $year = date('Y');

        $lastRecord = DeliveryWithdrawal::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastRecord) {

            $lastNumber = intval(substr($lastRecord->withdrawal_no, -3));
            $newNumber = $lastNumber + 1;
        } else {

            $newNumber = 1;
        }

        return 'SET-' . $year . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
    public function BasicCommonDetails($group_order_id)
    {
        $order = Orders::where('group_order_id', $group_order_id)->first();
        $seller = Customer::where('id', $order['seller_id'])->first();
        $storeData = SellerRequest::where('customer_id', $seller['id'])->first();
        $customerAddress = CustomerAddress::where('id', $order['customer_address_id'])->first();

        return [
            'order'            => $order,
            'seller'           => $seller,
            'store'            => $storeData,
            'address' => $customerAddress,
        ];
    }
    public function GetSettelmentNo($settlement_ids)
    {
        if (empty($settlement_ids)) {
            return '--';
        }

        // If JSON string like "[1,2]"
        if (is_string($settlement_ids) && str_starts_with(trim($settlement_ids), '[')) {
            $settlement_ids = json_decode($settlement_ids, true);
        }
        // If comma string like "1,2"
        elseif (is_string($settlement_ids)) {
            $settlement_ids = explode(',', $settlement_ids);
        }

        if (!is_array($settlement_ids) || count($settlement_ids) == 0) {
            return '--';
        }
        return Settelment::whereIn('id', $settlement_ids)
            ->pluck('settelment_no')   // ✅ spelling fixed
            ->implode(', ');
    }
    public function SellerOtherProfileInfo($sellerId)
    {
        $todayDate = date('Y-m-d');
        $settlementDays = Setting_Data()['settelment_payout_day'] ?? 0;

        $deliveredOrders = Orders::where('seller_id', $sellerId)->where('order_status', 6)->count();
        $firstOrder = Orders::where('seller_id', $sellerId)->where('order_status', 6)->orderBy('created_at', 'asc')->first();

        $avgOrders = "0.0";
        if ($firstOrder) {

            $firstDate = strtotime($firstOrder->created_at);
            $currentDate = time();

            $yearDiff  = date('Y', $currentDate) - date('Y', $firstDate);
            $monthDiff = date('m', $currentDate) - date('m', $firstDate);

            $totalMonths = ($yearDiff * 12) + $monthDiff + 1;

            if ($totalMonths > 0) {
                $avgOrders = number_format(($deliveredOrders / $totalMonths), 1);
            }
        }
        $pendingSettlement = Settelment::where('type', 1)->where('type_id', $sellerId)->where('status', 1)->sum('settlement_amount');
        $totalSettlement = Settelment::where('type', 1)->where('type_id', $sellerId)->where('status', 2)->sum('settlement_amount');
        // Next Payout Date
        $nextPayoutDate = '-';
        if ($pendingSettlement > 0) {

            $oldestPending = Settelment::where('type', 1)->where('type_id', $sellerId)
                ->where('status', 1)
                ->orderBy('created_at', 'asc')
                ->first();

            if ($oldestPending) {
                $nextPayoutDate = date(
                    'd M, Y',
                    strtotime($oldestPending->created_at . " +$settlementDays days")
                );
            }
        }

        $totalOrders = Orders::where('seller_id', $sellerId)->count();
        $totalEarning = Orders::where('seller_id', $sellerId)->where('order_status', 6)->sum('seller_commission');
        $todayOrders = Orders::where('seller_id', $sellerId)->where('order_status', 6)->whereDate('created_at', $todayDate)->count();
        $todayEarning = Orders::where('seller_id', $sellerId)->where('order_status', 6)->whereDate('created_at', $todayDate)->sum('seller_commission');
        $todayOutOfStock = Items::where('is_approved', 1)->where('seller_id', $sellerId)->where('total_stock', 0)->whereDate('updated_at', $todayDate)->count();

        $response = [
            'total_orders'  => (int) $totalOrders,
            'total_earning' => (int) $totalEarning,
            'avg_orders'    => $avgOrders,
            'today_orders'   => (int) $todayOrders,
            'today_earning'  => (int) $todayEarning,
            'out_of_stock'      => (int)$todayOutOfStock,
            'pending_settlement'  => (int) $pendingSettlement,
            'next_payout_date'    => $nextPayoutDate,
            'total_settlement'    => (int)$totalSettlement,
        ];
        return $response;
    }
    public function SellerPickupLocationInfo($group_order_id)
    {
        $orders = Orders::where('group_order_id', $group_order_id)->first();
        if (!$orders) {
            return [
                'pickup_location' => ''
            ];
        }
        $seller = SellerRequest::where('customer_id', $orders['seller_id'])->first();
        return $seller->store_address ?? '';
    }
    //SMTP 
    public function BasicNotiConfiguration($type)
    {
        if ($type != null) {
            $get = NotificationConfiguration::where('type', $type)->first();
        }
        return $get;
    }
    public function SetSmtpConfig()
    {
        $smtp = Smtp_Setting::latest()->first();
        if (isset($smtp) && $smtp != null && $smtp['status'] == 1) {

            // Build the SMTP mailer config in the Laravel 9/10/11 nested format.
            $encryption = $smtp->protocol ? strtolower($smtp->protocol) : 'tls';
            if (!in_array($encryption, ['tls', 'ssl', 'starttls'], true)) {
                $encryption = 'tls';
            }

            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp', [
                'transport'  => 'smtp',
                'host'       => $smtp->host,
                'port'       => (int) $smtp->port,
                'encryption' => $encryption,
                'username'   => $smtp->user,
                'password'   => $smtp->pass,
                'timeout'    => null,
                'auth_mode'  => null,
                'verify_peer' => false,
            ]);
            Config::set('mail.from', [
                'address' => $smtp->from_email,
                'name'    => $smtp->from_name,
            ]);

            // Reset the resolved mailer so the new config is picked up.
            try {
                app('mail.manager')->forgetMailers();
            } catch (\Throwable $e) {
                // older versions / no-op
            }
        }
        return true;
    }
    public function Send_Mail($type, $email, $array = [])
    {
        try {
            $this->SetSmtpConfig();
            $smtp = Smtp_Setting::latest()->first();

            if (isset($smtp) && $smtp['status'] == 1) {

                if ($type == 'register') {
                    $view = 'main.register';
                    $details = [
                        'title' => App_Name() . " - Registration",
                    ];
                } elseif ($type == 'login') {
                    $view = 'main.login';
                    $details = [
                        'title' => App_Name() . " - Login",
                    ];
                } else if ($type == 'forgot-password') {
                    $details = [
                        'title' => App_Name() . " - Forgot Password",
                        'view' => 'mail.forgot_password',
                        'details' => $array,
                    ];
                } else if ($type == 'delivery-boy-req-status') {
                    $statusTitle = match ($array['status']) {
                        'approved' => 'Delivery Partner Approved',
                        'rejected' => 'Delivery Partner Rejected',
                    };

                    $details = [
                        'title'   => App_Name() . ' - ' . $statusTitle,
                        'view'    => 'mail.delivery_boy_status',
                        'details' => $array,
                    ];
                } else if ($type == 'place-orders') {
                    $statusTitle = '';
                    if ($array['type'] == 'send-customer') {
                        $statusTitle = 'Order Placed';
                    } elseif ($array['type'] == 'send-seller') {
                        $statusTitle = 'New Order Received';
                    }
                    $details = [
                        'title'   => App_Name() . ' - ' . $statusTitle,
                        'view'    => 'mail.new_orders',
                        'details' => $array,
                    ];
                } else if ($type == 'order-status-change') {
                    $statusTitle = '';
                    if ($array['status_type'] == 'delivery-side') {

                        if ($array['status'] == 1) {
                            $statusTitle = 'Delivery Accepted';
                        } else if ($array['status'] == 2) {

                            if ($array['send_type'] == 'seller') {
                                $statusTitle = 'Order Picked Up';
                            } else if ($array['send_type'] == 'customer') {
                                $statusTitle = 'Order Out for Delivery';
                            }
                        } else if ($array['status'] == 4) {
                            $statusTitle = 'Order Delivered';
                        } else if ($array['status'] == 6) {
                            $statusTitle = 'Delivery Rejected';
                        } else {
                            $statusTitle = 'Order Status Update';
                        }
                    } else if ($array['status_type'] == 'seller-side') {
                        if ($array['status'] == 2) {
                            $statusTitle = 'Order Accepted';
                        } else if ($array['status'] == 3) {
                            $statusTitle = 'Order Preparing';
                        } else if ($array['status'] == 4) {
                            $statusTitle = 'Order Ready For Pickup';
                        } else if ($array['status'] == 8) {
                            $statusTitle = 'Order Rejected';
                        } else {
                            $statusTitle = 'Order Status Update';
                        }
                    }
                    // else if ($array['status_type'] == 'panel-side') {
                    //     if ($array['status'] == 2) {
                    //         $statusTitle = 'Order Confirmed';
                    //     } else if ($array['status'] == 3) {
                    //         $statusTitle = 'Order Shipped';
                    //     }
                    // }

                    $details = [
                        'title'   => App_Name() . ' - ' . $statusTitle,
                        'view'    => 'mail.order_status',
                        'details' => $array,
                    ];
                } else if ($type == 'order-assignment') {
                    $details = [
                        'title'   => App_Name() . ' - ' . "Order Assignment ",
                        'view'    => 'mail.order_assign',
                        'details' => $array,
                    ];
                }
                try {
                    $view = $details['view'];
                    Mail::to($email)->send(new \App\Mail\mail($details, $view));
                    return true;
                } catch (\Swift_TransportException $e) {
                    return true;
                }
            } else {
                return true;
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function SaveNotification($send_user_type, $noti_type, $customer_id, $seller_id, $delivery_boy_id, $content_id, $device_token, $device_type, $array = [])
    {
        try {
            $title = "";
            $message = "";

            if ($noti_type == 'forgot-password') {
                $title = "New Password Generated";
                $message = "A new password has been generated and sent to your registered email. Please check your inbox and update your password after login";
            }
            if ($noti_type == 'profile-update') {

                $title = "Profile Updated Successfully !";
                $message = "Your profile details have been updated. Please review them to ensure everything is accurate!";
            }
            if ($noti_type == 'account-delete') {
                if ($array['type'] == 'delivery-side') {
                    if ($array['status'] == 1) {
                        $title = "You’re Active Now 🚴‍♂️";
                        $message = "You are now active and ready to receive new delivery orders.";
                    } elseif ($array['status'] == 0) {
                        $title = "You’re Inactive Now ⛔";
                        $message = "You are now inactive. You won’t receive new delivery orders.";
                    }
                } else if ($array['type'] == 'seller-side') {
                    if ($array['status'] == 1) {
                        $title = "Seller Account Activated";
                        $message = "Your seller account has been activated successfully. Your products are now live and available for customers.";
                    } elseif ($array['status'] == 0) {
                        $title = "Seller Account Deactivated";
                        $message = "Your seller account has been deactivated. All your products are currently inactive and not visible to customers.";
                    }
                }
            }
            if ($noti_type == 'new-product-arrival') {
                $title = "New Item Added 🎉";
                $message = "Check out the latest item: \"{$array['title']}\". Available now!";
            }
            if ($noti_type == 'new-special-offers') {
                $title = "New Offer 🎁";
                $message = "Special offer \"{$array['title']}\" is now live! Tap to save more.";
            }
            if ($noti_type == 'new-added-categories') {
                $title = "New Category Available 🆕";
                $message = "Something new is here! \"{$array['title']}\" category has been added just for you.";
            }
            if ($noti_type == 'delivery-boy-req-status') {
                if ($array['status'] == 'approved') {
                    $title = "🎉 Request Approved!";
                    $message = "Your delivery boy request has been approved. You can now start accepting and delivering orders. Welcome onboard! 🚚";
                } else if ($array['status'] == 'rejected') {
                    $title = "❌ Request Rejected";
                    $message = "Your delivery boy request has been rejected. Please review your details or contact support for further assistance.";
                }
            }
            if ($noti_type == 'place-orders') {
                if ($array['type'] == 'send-customer') {
                    $orderIds = implode(', ', $array['group_order_id']);
                    $title = "✅ Order Placed Successfully!";
                    $message = "Hi {$array['customer_name']}, your order has been placed successfully.\n"
                        . "Order ID(s): #{$orderIds}\n"
                        . "Total Amount Paid: ₹{$array['total_amount']}.\n"
                        . "You will be notified once the seller ships your order.";
                } else if ($array['type'] == 'send-seller') {
                    $title = "📦 New Order Received!";
                    $message = "Hi {$array['seller_name']}, you have received a new order #{$array['group_order_id']} from {$array['customer_name']}. Please process it promptly.";
                }
            }
            if ($noti_type == 'resend-otp') {
                $title = "OTP Resent Successfully";
                $message = "Your new delivery OTP is " . " " . $array['new-otp']  . " " . "Please share this OTP with the delivery partner to complete your order.";
            }
            if ($noti_type == 'order-status-change') {
                if ($array['status_type'] == 'delivery-side') {

                    if ($array['status'] == 1) {

                        $title = "Order Accepted by Delivery Partner";
                        $message = "Delivery partner has accepted order " . $array['group_order_id'] . ".";
                    } else if ($array['status'] == 2) {

                        if ($array['send_type'] == 'seller') {

                            $title = "Order Picked Up";
                            $message = "Delivery partner has picked up order " . $array['group_order_id'] . ".";
                        } else if ($array['send_type'] == 'customer') {

                            $title = "Order Out for Delivery";
                            $message = "Your order " . $array['group_order_id'] . " is out for delivery 🚚. "
                                . "Delivery OTP: " . $array['delivery_otp'] . ". "
                                . "Please share this OTP only with the delivery partner.";
                        }
                    } else if ($array['status'] == 4) {

                        $title = "Order Delivered Successfully";
                        $message = "Your order " . $array['group_order_id'] . " has been delivered successfully. "
                            . "Thank you for shopping with us!";
                    }

                    // 6️⃣ Rejected
                    else if ($array['status'] == 6) {

                        $title = "Delivery Rejected";
                        $message = "Delivery partner has rejected order " . $array['group_order_id'] . ". "
                            . "Please assign another delivery partner.";
                    }
                } else if ($array['status_type'] == 'seller-side') {
                    if ($array['status'] == 2) {
                        $title = "Order Confirmed";
                        $message = "Great news! Your order #" . $array['group_order_id'] . " has been accepted by the seller and is now being prepared.";
                    } else if ($array['status'] == 3) {
                        $title = "Order is Being Prepared";
                        $message = "Your order #" . $array['group_order_id'] . " is currently being prepared. We’ll notify you once it’s ready for pickup.";
                    } else if ($array['status'] == 4) {
                        $title = "Order Packed & Ready";
                        $message = "Good news! Your order #" . $array['group_order_id'] . " has been packed and is ready for pickup by our delivery partner.";
                    } else if ($array['status'] == 8) {
                        $title = "Order Rejected";
                        $message = "We’re sorry. Your order #" . $array['group_order_id'] . " was rejected by the seller. If any payment was made, it will be refunded as per our policy.";
                    }
                }
                // else if ($array['status_type'] == 'panel-side') {
                //     if ($array['status'] == 2) {
                //         $title = "Order Confirmed";
                //         $message =  "Your order #{$array['group_order_id']} has been confirmed and is being prepared.";
                //     } else if ($array['status'] == 3) {
                //         $title = "Order Shipped";
                //         $message = "Your order #{$array['group_order_id']} has been shipped and will be picked up by the delivery partner shortly.";
                //     } else if ($array['status'] == 6) {
                //         $title = "Order Rejected by Seller";
                //         $message = "Your order #" . $array['group_order_id'] . " was not accepted by the seller. We are working to reassign it. Thank you for your patience.";
                //     }
                // }
            }
            if ($noti_type == 'order-assignment') {
                $title = "New Order Assigned 🚚";
                $message = "Order #{$array['group_order_id']} has been assigned to you. Please open the app to start delivery.";
            }
            if ($noti_type == 'reviews') {
                $title = "New Product Review ⭐";
                $message = 'Your product "' . $array['item_name'] . '" has received a new review. Check it now.';
            }
            if ($noti_type == 'cart-abandonment-reminder') {
                $title = "Don't forget your cart item: " . " " . $array['item_name'] . " is waiting!";
                $message = "You left" . " " . $array['item_name'] . " " . "in your cart. Complete your purchase before it’s gone!";
            }
            if ($noti_type == 'wishlist-alert') {

                $title = "Your wishlist item is waiting!";
                $message = "{$array['item_name']} is still in your wishlist. Check it out before someone else does!";
            }
            if ($noti_type == 'product-stock-alert') {
                $title = "Stock Alert ⚠️";
                $message = "The product \"{$array['item_name']}\" is now out of stock. Please update the stock to continue selling.";
            }

            $data['type'] = $noti_type;
            $data['customer_id'] = $customer_id;
            $data['seller_id'] = $seller_id;
            $data['delivery_boy_id'] = $delivery_boy_id;
            $data['content_id'] = $content_id;
            $data['title'] = $title;
            $data['message'] = $message;
            $data['image'] = "";
            $data['status'] = 1;

            Notification::insert($data);

            if ($send_user_type == 'customer') {
                $this->send_customer_push_notification($device_type, $device_token, $title, $message, $array);
            } elseif ($send_user_type == 'seller') {
                $this->send_seller_push_notification($device_type, $device_token, $title, $message, $array);
            } elseif ($send_user_type === 'deliveryboy') {
                $this->send_deliveryboy_push_notification($device_type, $device_token, $title, $message, $array);
                return true;
            } elseif ($send_user_type === 'all') {
                $noti_array = array(
                    'notification_type' => $noti_type,
                    'content_id' => $array['content_id'],
                    'content_name' => $array['title'],
                    'title' => $title,
                    'description' => $message,
                    'image' => $array['imageURL'] ?? '',
                    'type' => $array['type'],
                );

                $this->send_notification($noti_array);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function send_customer_push_notification($device_type, $device_token, $title, $message, $array)
    {
        $toUser = [$device_token];

        $setting = Setting_Data();
        $ONESIGNAL_APP_ID = $setting['c_onesignal_app_id'];
        $ONESIGNAL_REST_KEY = $setting['c_onesignal_rest_key'];

        $fields = [
            'app_id' => $ONESIGNAL_APP_ID,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'include_player_ids' => $toUser, // ✅ Works for both Android & iOS
            'data' => [
                'notification_type' => $array['notification_type'],
                'content_id' => $array['group_order_id'],
                'order_status' => $array['status'],
                'type' => 'notification',
                'timestamp' => now()->timestamp
            ]
        ];
        // (Optional) Add platform targeting if you need
        if ($device_type == 1) {
            $fields['android_channel_id'] = $setting['onesignal_android_channel_id'] ?? null;
        } elseif ($device_type == 2) {
            $fields['ios_badgeType'] = 'Increase';
            $fields['ios_badgeCount'] = 1;
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $ONESIGNAL_REST_KEY
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        // Optional: log or decode response to check for errors
        $result = json_decode($response, true);

        if (isset($result['errors'])) {
            \Log::error('OneSignal Error: ' . json_encode($result['errors']));
        }

        return $response;
    }
    public function send_seller_push_notification($device_type, $device_token, $title, $message, $array)
    {
        $toUser = [$device_token];

        $setting = Setting_Data();
        $ONESIGNAL_APP_ID = $setting['s_onesignal_app_id'];
        $ONESIGNAL_REST_KEY = $setting['s_onesignal_rest_key'];

        $fields = [
            'app_id' => $ONESIGNAL_APP_ID,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'include_player_ids' => $toUser, // ✅ Works for both Android & iOS
            'data' => [
                'notification_type' => $array['notification_type'],
                'content_id' => $array['group_order_id'],
                'order_status' => $array['status'],
                'type' => 'notification',
                'timestamp' => now()->timestamp
            ]
        ];

        // (Optional) Add platform targeting if you need
        if ($device_type == 1) {
            $fields['android_channel_id'] = $setting['onesignal_android_channel_id'] ?? null;
        } elseif ($device_type == 2) {
            $fields['ios_badgeType'] = 'Increase';
            $fields['ios_badgeCount'] = 1;
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $ONESIGNAL_REST_KEY
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        // Optional: log or decode response to check for errors
        $result = json_decode($response, true);

        if (isset($result['errors'])) {
            \Log::error('OneSignal Error: ' . json_encode($result['errors']));
        }

        return $response;
    }
    public function send_deliveryboy_push_notification($device_type, $device_token, $title, $message, $array)
    {
        $toUser = [$device_token];

        $setting = Setting_Data();
        $ONESIGNAL_APP_ID = $setting['d_onesignal_app_id'];
        $ONESIGNAL_REST_KEY = $setting['d_onesignal_rest_key'];

        $fields = [
            'app_id' => $ONESIGNAL_APP_ID,
            'headings' => ['en' => $title],
            'contents' => ['en' => $message],
            'include_player_ids' => $toUser, // ✅ Works for both Android & iOS
            'data' => [
                'notification_type' => $array['notification_type'],
                'content_id' => $array['group_order_id'],
                'order_status' => $array['status'],
                'type' => 'notification',
                'timestamp' => now()->timestamp
            ]
        ];

        // (Optional) Add platform targeting if you need
        if ($device_type == 1) {
            $fields['android_channel_id'] = $setting['onesignal_android_channel_id'] ?? null;
        } elseif ($device_type == 2) {
            $fields['ios_badgeType'] = 'Increase';
            $fields['ios_badgeCount'] = 1;
        }

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $ONESIGNAL_REST_KEY
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);

        curl_close($ch);

        // Optional: log or decode response to check for errors
        $result = json_decode($response, true);

        if (isset($result['errors'])) {
            \Log::error('OneSignal Error: ' . json_encode($result['errors']));
        }

        return $response;
    }
    public function send_notification($array)
    {

        $settingData = Setting_Data();

        if ($array['type'] == 'customer') {
            $ONESIGNAL_APP_ID = $settingData['c_onesignal_app_id'];
            $ONESIGNAL_REST_KEY = $settingData['c_onesignal_rest_key'];
        } else if ($array['type'] == 'deliveryboy') {
            $ONESIGNAL_APP_ID = $settingData['d_onesignal_app_id'];
            $ONESIGNAL_REST_KEY = $settingData['d_onesignal_rest_key'];
        } else if ($array['type'] == 'seller') {
            $ONESIGNAL_APP_ID = $settingData['s_onesignal_app_id'];
            $ONESIGNAL_REST_KEY = $settingData['s_onesignal_rest_key'];
        }
        $fields = array(
            'app_id' => $ONESIGNAL_APP_ID,
            'included_segments' => array('All'),
            'data' => $array,
            'headings' => array("en" => $array['title']),
            'contents' => array("en" => $array['description']),
            'big_picture' => $array['image'],
        );
        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $ONESIGNAL_REST_KEY,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);

        curl_close($ch);
    }
    public function ReminderWishlistItem()
    {
        $config = NotificationConfiguration::where('type', 16)->first();

        if (!$config || $config->status != 1 || $config->send_notification != 1 || $config->week_day == 0) {
            return;
        }

        $daysLimit = $config->week_day;

        $wishlistItems = Wishlist::with('customer', 'item')->get();

        foreach ($wishlistItems as $wishlist) {

            if (!$wishlist->item || !$wishlist->customer) {
                continue;
            }

            $bookmarkDate   = strtotime(date('Y-m-d', strtotime($wishlist->created_at)));
            $currentDate    = strtotime(date('Y-m-d'));
            $daysDifference = floor(($currentDate - $bookmarkDate) / (60 * 60 * 24));

            if ($daysDifference != $daysLimit) {
                continue;
            }

            $alreadySent = Notification::where('type', 'wishlist-alert')->where('user_id', $wishlist->customer->id)->where('content_id', $wishlist->item->id)->exists();

            if ($alreadySent) {
                continue; // duplicate stop
            }


            $array = [
                'item_name' => $wishlistItems->item->name,
            ];

            $this->SaveNotification('customer', $config['type'], $wishlistItems->customer_id->id, 0, 0, $wishlistItems->item->id, $wishlistItems->customer->device_token, $wishlistItems->customer->device_type, $array);
        }
    }
    public function ReminderCartItem()
    {

        $config = NotificationConfiguration::where('type', 'cart-abandonment-reminder')->first();

        if (!$config || $config->status != 1 || $config->send_notification != 1 || $config->week_day == 0) {
            return;
        }
        $daysLimit = $config->week_day;

        $cartItems = AddToCart::with('customer', 'item')->get();

        foreach ($cartItems as $cart) {

            if (!$cart->item || !$cart->customer) {
                continue;
            }

            $cartDate       = strtotime(date('Y-m-d', strtotime($cart->created_at)));
            $currentDate    = strtotime(date('Y-m-d'));
            $daysDifference = floor(($currentDate - $cartDate) / (60 * 60 * 24));

            if ($daysDifference != $daysLimit) {
                continue;
            }

            $alreadySent = Notification::where('type', 'cart-abandonment-reminder')->where('customer_id', $cart->customer->id)->where('content_id', $cart->item->id)->exists();

            if ($alreadySent) {
                continue;
            }

            $array = [
                'item_name' => $cart->item->name,
            ];

            $this->SaveNotification('customer', $config['type'], $cart->customer_id->id, 0, 0, $cart->item->id, $cart->customer->device_token, $cart->customer->device_type, $array);
        }
    }
    public function ReminderDeliveryOrders()
    {

        $config = NotificationConfiguration::where('type', 'orders-reminders')->first();

        if (!$config || $config->status != 1 || $config->send_notification != 1 || $config->week_day == 0) {
            return;
        }
        $currentTime = strtotime(date('Y-m-d H:i:s'));
        $assignments = DeliveryAssignment::with('deliveryBoy')->where('status', 2)->get();

        foreach ($assignments as $assignment) {
            if (!$assignment->deliveryBoy || !$assignment->estimated_delivery_time) {
                continue;
            }

            $deliveryTime = strtotime($assignment->estimated_delivery_time);

            $diffInMinutes = floor(($deliveryTime - $currentTime) / 60);

            if ($diffInMinutes <= 1440 && $diffInMinutes > 1380) {

                $alreadySent = Notification::where('type', 'pickup-reminder-day')->where('delivery_boy_id', $assignment->delivery_boy_id)->where('content_id', $assignment->group_order_id)->exists();

                if (!$alreadySent) {
                    $array = [
                        'group_order_id' => $assignment->group_order_id,
                        'reminder_type'  => '1_day_before',
                    ];

                    $this->SaveNotification('deliveryboy', 'pickup-reminder-day', 0, 0, $assignment->delivery_boy_id, $assignment->group_order_id, $assignment->deliveryBoy->device_token, $assignment->deliveryBoy->device_type, $array);
                }
            }
            if ($diffInMinutes <= 60 && $diffInMinutes > 50) {

                $alreadySent = Notification::where('type', 'pickup-reminder-hour')
                    ->where('delivery_boy_id', $assignment->delivery_boy_id)
                    ->where('content_id', $assignment->group_order_id)
                    ->exists();

                if (!$alreadySent) {

                    $array = [
                        'group_order_id' => $assignment->group_order_id,
                        'reminder_type'  => '1_hour_before',
                    ];

                    $this->SaveNotification('deliveryboy', 'pickup-reminder-hour', 0, 0, $assignment->delivery_boy_id, $assignment->group_order_id, $assignment->deliveryBoy->device_token, $assignment->deliveryBoy->device_type, $array);
                }
            }
        }
    }
    public function OtherSendMail($type, $orderStatus, $array)
    {

        $this->SetSmtpConfig();

        $smtp = Smtp_Setting::latest()->first();
        if (isset($smtp) && $smtp != false && $smtp['status'] == 1) {
            if ($type == 7) {

                $title = App_Name() . " - New Serviceable Zones Assigned!";
                $view = 'mail.assignzone';

                $details = [
                    'title' => $title,
                    'details' => $array,

                ];
                $send_email = $array['email'];
            } elseif ($type == 10) {

                if ($orderStatus == 2) {
                    $title = App_Name() . " - Order Confirmed ";
                } elseif ($orderStatus == 3) {
                    $title = App_Name() . " - Order Shipped ";
                } elseif ($orderStatus == 4) {
                    $title = App_Name() . "- Out for Delivery";
                } elseif ($orderStatus == 5) {
                    $title = App_Name() . "- Order Delivered";
                } elseif ($orderStatus == 6) {
                    $title = App_Name() . "- Order Cancelled";
                } elseif ($orderStatus == 8) {
                    $title = App_Name() . "- Return in Process";
                } elseif ($orderStatus == 9) {
                    $title = App_Name() . "- Order Returned";
                } elseif ($orderStatus == 10) {
                    $title = App_Name() . "- Return Rejected";
                }
                $view = 'mail.order_update';

                $details = [
                    'title' => $title,
                    'details' => $array,
                ];
                $send_email = $array['email'];
            } elseif ($type == 11) {

                $title = App_Name() . " - New Delivery Assignment!";
                $view = 'mail.assignorder';

                $details = [
                    'title' => $title,
                    'details' => $array,
                    'type' => $type,
                ];
                $send_email = $array['email'];
            } elseif ($type == 12) {

                $title = App_Name() . " - Return Pickup Assigned!";
                $view = 'mail.assignorder';

                $details = [
                    'title' => $title,
                    'details' => $array,
                    'type' => $type,
                ];
                $send_email = $array['email'];
            } elseif ($type == 9) {

                $title = App_Name() . " - New Order Received";
                $view = 'mail.neworder';

                $details = [
                    'title' => $title,
                    'details' => $array,
                ];
                $send_email = $array['email'];
            }

            if (filter_var($send_email, FILTER_VALIDATE_EMAIL)) {
                try {
                    Mail::to($send_email)->send(new \App\Mail\mail($details, $view));
                } catch (\Swift_TransportException $e) {
                    return true;
                } catch (\Exception $e) {
                    return true;
                }
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
}
