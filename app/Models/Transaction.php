<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'tbl_transaction';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'customer_id' => 'integer',
        'delivery_boy_id' => 'integer',
        'group_order_id' => 'string',
        'transaction_id' => 'string',
        'price' => 'integer',
        'status' => 'integer',
    ];
}
