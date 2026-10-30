<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsFaq extends Model
{
    use HasFactory;

    protected $table = 'tbl_item_faq';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'question' => 'string',
        'answer' => 'string',
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
}
