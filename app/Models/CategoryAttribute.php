<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    // public function items(){
    //     return $this->hasMany(CategoryAttributeItem::class, 'category_attribute_id');
    // }
    public function categoryAttributeItems()
    {
        return $this->hasMany('App\Models\CategoryAttributeItem', 'category_attribute_id');
    }
}
