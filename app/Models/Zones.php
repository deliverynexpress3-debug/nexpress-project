<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zones extends Model
{
    use HasFactory;

    protected $table = 'tbl_zones';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'zone_name' => 'string',
        'status' => 'integer',
    ];
}
