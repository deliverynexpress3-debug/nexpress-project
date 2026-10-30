<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonesValue extends Model
{
    use HasFactory;

    protected $table = 'tbl_zone_value';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'zone_id' => 'integer',
        'zipcode_id' => 'integer',
        'status' => 'integer',
    ];

    public function zipcode()
    {
        return $this->belongsTo(ZipCode::class, 'zipcode_id');
    }
}
