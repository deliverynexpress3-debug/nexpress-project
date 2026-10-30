<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attributes extends Model
{
    use HasFactory;

    protected $table = 'tbl_attributes';
    protected $guarded = array();

    protected $casts = [
        'id' => 'integer',
        'category_id' => 'string',
        'title' => 'string',
        'field_type' => 'integer',
        'icon' => 'string',
        'is_required' => 'integer',
        'status' => 'integer',

    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public static function DeleteAllData($id)
    {
        $common = new Common();

        AttributesOption::where('attributes_id', $id)->delete();

        $getItemAttributes = ItemsAttributes::where('attributes_id', $id)->get();
        foreach ($getItemAttributes as $value) {
            $common->deleteImageToFolder('product', $value['option']);
            $value->delete();
        }
    }
}
