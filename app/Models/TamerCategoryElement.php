<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamerCategoryElement extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function categoryElement()
    {
        return $this->belongsTo('App\Models\CategoryElement', 'category_element_id');
    }
}
