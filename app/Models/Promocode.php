<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;

    protected $table = 'tbl_promo_code';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'seller_id' => 'integer',
        'name' => 'string',
        'promo_code' => 'string',
        'type' => 'integer',
        'discount_amount' => 'integer',
        'usage_limit' => 'integer',
        'user_limit_per_user' => 'integer',
        'image' => 'string',
        'status' => 'integer',

    ];
}
