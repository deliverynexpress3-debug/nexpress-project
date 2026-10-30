<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsImages extends Model
{
    use HasFactory;

    protected $table = 'tbl_item_img';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'image' => 'string',
        'status' => 'integer',

    ];
}
