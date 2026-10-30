<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class DeliveryBoy extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_delivery_boy';
    protected $guarded = array();

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'id' => 'integer',
        'zone_id' => 'string',
        'user_name' => 'string',
        'full_name' => 'string',
        'email' => 'string',
        'password' => 'string',
        'country_code' => 'string',
        'mobile_number' => 'string',
        'country_name' => 'string',
        'image' => 'string',
        'vehicle_name' => 'string',
        'vehicle_number' => 'string',
        'type' => 'integer',
        'device_type' => 'integer',
        'device_token' => 'string',
        'wallet_balance' => 'integer',
        'advance_balance' => 'integer',
        'verification_status' => 'integer',
        'status' => 'integer',
    ];
    public static function DeleteAllData($id)
    {
        DeliveryAssignment::where('delivery_boy_id', $id)->update(['delivery_boy_id' => 0]);
    }
}
