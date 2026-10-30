<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTracking extends Model
{
    use HasFactory;

    protected $table = 'tbl_notification_tracking';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'type_id' => 'integer',
        'notification_id' => 'integer',
        'status' => 'integer',
    ];
}
