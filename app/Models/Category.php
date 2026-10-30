<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Common;

class Category extends Model
{
    use HasFactory;

    protected $table = 'tbl_category';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'parent_category_id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'sort_order' => 'integer',
        'status' => 'integer',
    ];

    public function sub_category()
    {
        return $this->hasMany(Category::class, 'parent_category_id')->with('sub_category');
    }
}
