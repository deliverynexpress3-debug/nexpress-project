<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackIntrest extends Model
{
    use HasFactory;

    protected $table = 'tbl_customer_intrest';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'item_id' => 'integer',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'count' => 'integer',
        'status' => 'integer',
    ];
}
