<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamerCategoryService extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function categoryService()
    {
        return $this->belongsTo('App\Models\CategoryService', 'category_service_id');
    }

}
