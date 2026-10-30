<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'tbl_section';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'section_type' => 'integer',
        'is_home_screen' => 'integer',
        'title' => 'string',
        'short_title' => 'string',
        'screen_layout' => 'string',
        'type_id' => 'integer',
        'content_id' => 'integer',
        'category_id' => 'string',
        'sub_category_id' => 'integer',
        'brand_id' => 'integer',
        'seller_id' => 'integer',
        'product_content_type	' => 'integer',
        'selling_type' => 'integer',
        'include_low_price' => 'integer',
        'include_high_price' => 'integer',
        'order_by_upload' => 'integer',
        'order_by_like' => 'integer',
        'no_of_content' => 'integer',
        'no_of_row' => 'integer',
        'background_type' => 'string',
        'background_value' => 'string',
        'view_all' => 'integer',
        'load_more' => 'integer',
        'sortable' => 'integer',
        'status' => 'integer',
    ];
    public function item()
    {
        return $this->belongsTo(Items::class, 'content_id');
    }
    public function offers()
    {
        return $this->belongsTo(Offers::class, 'content_id');
    }
}
