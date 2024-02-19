<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttributeItem extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function categoryAttribute()
    {
        return $this->belongsTo('App\Models\CategoryAttribute', 'category_attribute_id');
    }
}
