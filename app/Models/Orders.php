<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    protected $table = 'tbl_orders';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'customer_id' => 'integer',
        'item_total' => 'integer',
        'delivery_charges' => 'integer',
        'handling_charges' => 'integer',
        'donation_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_payable_amount' => 'integer',
        'promo_codes_id' => 'integer',
        'customer_address_id' => 'integer',
        'delivery_instructor_id' => 'string',
        'payment_method' => 'integer',
        'payment_method' => 'integer',
        'order_status' => 'integer',
        'is_assigned' => 'integer',
        'order_notes' => 'string',
        'seller_commission' => 'integer',
        'delivery_boy_commission' => 'integer',
        'delivery_otp' => 'string',


    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function customer_address()
    {
        return $this->belongsTo(CustomerAddress::class, 'customer_address_id');
    }
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'seller_id');
    }
}
