<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'tbl_wishlist';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'item_id' => 'integer',
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
