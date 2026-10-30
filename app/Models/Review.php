<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'tbl_review';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'customer_id' => 'integer',
        'content_id' => 'integer',
        'rating' => 'integer',
        'review' => 'string',
        'image' => 'string',
        'status' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function item()
    {
        return $this->belongsTo(Items::class, 'content_id');
    }
    public function seller()
    {
        return $this->belongsTo(Customer::class, 'content_id');
    }
}
