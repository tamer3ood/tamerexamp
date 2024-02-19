<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function setNameEnAttribute($value)
    {
        $this->attributes['name_en'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-');


        // Str::slug('Laravel 5 Framework', '-');

    }

    public function categoryAttributes()
    {
        return $this->hasMany('App\Models\CategoryAttribute', 'category_id');
    }
    public function subCategories()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
    public function parentCategory()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }
    public function categoryElements()
    {
        return $this->hasMany('App\Models\CategoryElement', 'category_id');
    }
    public function categoryServices()
    {
        return $this->hasMany('App\Models\CategoryService', 'category_id');
    }
}
