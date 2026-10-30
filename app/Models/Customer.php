<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_customer';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'is_seller' => 'integer',
        'user_name' => 'string',
        'full_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'mobile_number' => 'string',
        'image' => 'string',
        'device_type' => 'integer',
        'device_token' => 'string',
        'wallet_amount' => 'integer',
        'gst_number' => 'string',
        'gst_firm_name' => 'string',
        'printer_mac_address' => 'string',
        'type' => 'integer',
        'status' => 'integer',
    ];
    public static function CustomerDeleteAllData($id)
    {
        AddToCart::where('customer_id', $id)->delete();
        CustomerAddress::where('customer_id', $id)->delete();
        Review::where('customer_id', $id)->delete();
        Wishlist::where('customer_id', $id)->delete();
        Orders::where('customer_id', $id)->delete();
    }
    public static function SellerDeleteAllData($id)
    {

        $common = new Common();
        $items = Items::where('seller_id', $id)->get();

        foreach ($items as $value) {
            $common->deleteImageToFolder('product', $value['portrait_img']);
            $common->deleteImageToFolder('product', $value['landscape_img']);
            $common->deleteImageToFolder('product', $value['url']);

            $getItemAtt = ItemsAttributes::where('item_id', $value['id'])->get();
            foreach ($getItemAtt as $item) {
                $common->deleteImageToFolder('product', $item['option']);
                $item->delete();
            }

            ItemsFaq::where('item_id', $value['id'])->delete();
            ItemsImages::where('item_id', $value['id'])->delete();
            Offers::where('item_id', $value['id'])->delete();
            Orders::where('item_id', $value['id'])->delete();
            ReturnRequest::where('item_id', $value['id'])->delete();
            Review::where('item_id', $value['id'])->delete();
            Wishlist::where('item_id', $value['id'])->delete();
            AddToCart::where('item_id', $id)->delete();

            $value->delete();
        }
    }
}
