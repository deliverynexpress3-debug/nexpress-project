<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'tbl_banner';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'is_home_screen' => 'integer',
        'type_id' => 'integer',
        'content_type' => 'integer',
        'content_id' => 'integer',
        'status' => 'integer',
    ];
    public function item()
    {
        return $this->belongsTo(Items::class, 'content_id');
    }
    public function offers()
    {
        return $this->belongsTo(Offers::class, 'content_id');
    }
}
