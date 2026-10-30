<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class OrdersTracking extends Authenticatable
{
    use Notifiable;

    protected $table = 'tbl_order_trackig';
    protected $guarded = array();
    
    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'status' => 'integer',
    ];
}
