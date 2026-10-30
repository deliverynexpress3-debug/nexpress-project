<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding_Screen extends Model
{
    use HasFactory;

    protected $table = 'tbl_onboarding_screen';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'title' => 'string',
        'image' => 'string',
        'description' => 'string',
        'status' => 'integer',
    ];
}
