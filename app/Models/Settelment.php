<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settelment extends Model
{
    use HasFactory;

    protected $table = 'tbl_settlement';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'settelment_no' => 'string',
        'type' => 'integer',
        'type_id' => 'integer',
        'commission_amount' => 'integer',
        'adjustment_amount' => 'integer',
        'settlement_amount' => 'integer',
        'status' => 'integer',
    ];
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'type_id');
    }
    public function deliveryboy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'type_id');
    }
    public function getUser()
    {
        if ($this->type == 1) {
            return $this->seller;
        }

        if ($this->type == 2) {
            return $this->deliveryboy;
        }

        return null;
    }
}
