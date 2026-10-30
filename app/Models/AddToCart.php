<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AddToCart extends Model
{
    use HasFactory;

    protected $table = 'tbl_add_to_card';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'item_id' => 'integer',
        'quantity' => 'integer',
        'variation' => 'string',
        'total_price' => 'integer',
        'status' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
}
