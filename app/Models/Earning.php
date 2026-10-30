<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Earning extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_earning';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'admin_earning' => 'integer',
        'seller_id' => 'integer',
        'seller_earning' => 'integer',
        'total_price' => 'integer',
        'status' => 'integer',
    ];
}
