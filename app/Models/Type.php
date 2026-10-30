<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;

    protected $table = 'tbl_type';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'bg_color' => 'string',
        'icon' => 'string',
        'banner_image' => 'string',
        'sort_order' => 'integer',
        'status' => 'integer',
    ];
}
