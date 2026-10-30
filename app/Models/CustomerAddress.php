<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class CustomerAddress extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_customer_address';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'type' => 'integer',
        'address' => 'string',
        'latitude' => 'string',
        'longitude' => 'string',
        'area' => 'string',
        'city' => 'string',
        'state' => 'string',
        'country' => 'string',
        'pincode' => 'string',
        'status' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
