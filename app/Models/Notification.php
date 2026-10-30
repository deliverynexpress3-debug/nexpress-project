<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'string',
        'customer_id' => 'integer',
        'seller_id' => 'integer',
        'delivery_boy_id' => 'integer',
        'content_id' => 'string',
        'title' => 'string',
        'message' => 'string',
        'image' => 'string',
        'status' => 'integer',

    ];
}
