<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    use HasFactory;

    protected $table = 'tbl_item';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'seller_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'brand_id' => 'integer',
        'product_type' => 'integer',
        'name' => 'string',
        'tag_id' => 'string',
        'unit' => 'string',
        'per_unit' => 'integer',
        'tax_id' => 'string',
        'tax_include_price' => 'integer',
        'total_allowed_quantity' => 'integer',
        'minimum_order_quantity' => 'integer',
        'minimum_free_order_quantity' => 'integer',
        'is_cod' => 'integer',
        'is_cancelable' => 'integer',
        'is_returnable' => 'integer',
        'enable_stock' => 'integer',
        'total_stock' => 'integer',
        'stock_status_id' => 'integer',
        'price' => 'integer',
        'sort_description' => 'string',
        'long_description' => 'string',
        'video_type' => 'integer',
        'url' => 'string',
        'portrait_img' => 'string',
        'landscape_img' => 'string',
        'is_approved' => 'integer',
        'status' => 'integer',

    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'seller_id');
    }

    public static function DeleteAllData($id)
    {
        $common = new Common();

        $Attributes = ItemsAttributes::where('item_id', $id)->get();
        foreach ($Attributes as $value) {
            $common->deleteImageToFolder('product', $value['option']);
            $value->delete();
        }
        $itemImage = ItemsImages::where('item_id', $id)->get();
        foreach ($itemImage as $image) {
            $common->deleteImageToFolder('product', $image['image']);
            $image->delete();
        }
        Banner::where('content_type', 2)->where('content_id', $id)->delete();
        ItemsFaq::where('item_id', $id)->delete();
        Offers::where('item_id', $id)->delete();
        // Orders::where('item_id', $id)->delete();
        ReturnRequest::where('item_id', $id)->delete();
        Review::where('content_id', $id)->delete();
        Wishlist::where('item_id', $id)->delete();
        AddToCart::where('item_id', $id)->delete();
    }
}
