<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SellerRequest extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_seller_request';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'customer_id' => 'integer',
        'store_name' => 'string',
        'store_url' => 'string',
        'store_logo' => 'string',
        'store_address' => 'string',
        'bank_name' => 'string',
        'bank_holder_name' => 'string',
        'account_no' => 'string',
        'ifsc_code' => 'string',
        'bank_address' => 'string',
        'bank_proof' => 'string',
        'status' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
}
