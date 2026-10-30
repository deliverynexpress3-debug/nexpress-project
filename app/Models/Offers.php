<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offers extends Model
{
    use HasFactory;

    protected $table = 'tbl_offers';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'item_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'type' => 'integer',
        'discount_amount' => 'integer',
        'image' => 'string',
        'banner_image' => 'string',
        'status' => 'integer',

    ];
    public function item()
    {
        return $this->belongsTo(Items::class, 'item_id');
    }
}
