<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersItems extends Model
{
    use HasFactory;

    protected $table = 'tbl_order_item';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'group_order_id' => 'string',
        'order_number' => 'string',
        'customer_id' => 'integer',
        'item_id' => 'integer',
        'seller_id' => 'integer',
        'variation' => 'string',
        'item_price' => 'integer',
        'order_quantity' => 'integer',
        'order_quantity_amount' => 'integer',
    ];
    
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'seller_id');
    }
}
