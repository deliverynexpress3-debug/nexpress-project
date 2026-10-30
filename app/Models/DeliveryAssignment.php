<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAssignment extends Model
{
    use HasFactory;

    protected $table = 'tbl_delivery_assignment';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'delivery_boy_id' => 'integer',
        'pickup_time' => 'string',
        'pickup_location' => 'string',
        'estimated_delivery_time' => 'string',
        'status' => 'integer',
    ];

    public function group_order()
    {
        return $this->belongsTo(Orders::class, 'group_order_id',  'group_order_id');
    }
    public function deliveryboy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id');
    }
}
