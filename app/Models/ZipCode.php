<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZipCode extends Model
{
    use HasFactory;

    protected $table = 'tbl_zipcode';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'city_id' => 'integer',
        'zipcode' => 'integer',
        'area_name' => 'string',
        'status' => 'integer',
    ];
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
