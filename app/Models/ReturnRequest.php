<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $table = 'tbl_return_request';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'order_id' => 'integer',
        'customer_id' => 'integer',
        'item_id' => 'integer',
        'return_reason_id' => 'integer',
        'status' => 'integer',

    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
    public function return_reason()
    {
        return $this->belongsTo(Reason::class, 'return_reason_id');
    }
}
