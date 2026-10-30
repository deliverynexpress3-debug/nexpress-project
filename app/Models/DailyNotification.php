<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyNotification extends Model
{
    use HasFactory;

    protected $table = 'tbl_daily_notification';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'message' => 'string',
        'day' => 'integer',
        'type' => 'integer',
        'status' => 'integer',

    ];
}
