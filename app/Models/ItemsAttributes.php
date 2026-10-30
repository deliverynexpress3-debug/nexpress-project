<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsAttributes extends Model
{
    use HasFactory;

    protected $table = 'tbl_item_attributes_option';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'attributes_id' => 'integer',
        'option' => 'string',
        'field_type' => 'integer',
        'status' => 'integer',

    ];
    public function attributes()
    {
        return $this->belongsTo(Attributes::class, 'attributes_id');
    }
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
}
