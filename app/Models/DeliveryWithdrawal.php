<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryWithdrawal extends Model
{
    use HasFactory;

    protected $table = 'tbl_delivery_withdrawal';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'withdrawal_no' => 'string',
        'type'  => 'integer',
        'type_id' => 'integer',
        'total_amount' => 'integer',
        'settlement_ids' => 'string',
        'payment_method' => 'string',
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
