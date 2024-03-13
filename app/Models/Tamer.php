<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Tamer extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function setTitleAttribute($value)
    {
        // $slug = Str::of('Laravel Framework')->slug('-');

        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::of($value)->slug('-') . '-' . time();
        //  $this->attributes['slug']= Str::of($value)->slug('-') . '-' . time() . Auth::id();
    }
    public function talent()
    {
        return $this->belongsTo('App\Models\User', 'talent_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo('App\Models\Category', 'sub_category_id');
    }


    public function tamerCategoryAttributeItems()
    {
        return $this->belongsToMany('App\Models\CategoryAttributeItem', 'tamer_category_attribute_item', 'tamer_id', 'category_attribute_item_id');

    }
    public function tamerSearchTags()
    {
        return $this->belongsToMany('App\Models\SearchTag', 'tamer_search_tag', 'tamer_id', 'search_tag_id');

    }

    public function tamerCategoryElements()
    {
        return $this->hasMany('App\Models\TamerCategoryElement');
    }
    public function tamerCategoryServices()
    {
        return $this->hasMany('App\Models\TamerCategoryService');
    }
    public function tamerCategoryServices_basicPk()
    {
        return $this->hasMany('App\Models\TamerCategoryService')->where('is_basic_pk', 1);
    }
    public function tamerCategoryServices_standardPk()
    {
        return $this->hasMany('App\Models\TamerCategoryService')->where('is_standard_pk', 1);
    }
    public function tamerCategoryServices_advancedPk()
    {
        return $this->hasMany('App\Models\TamerCategoryService')->where('is_advanced_pk', 1);
    }
    public function tamerAddOns()
    {
        return $this->hasMany('App\Models\TamerAddOn');
    }

    public function tamerFiles()
    {
        return $this->hasMany('App\Models\TamerFile');
    }

    public function tamerRequirements()
    {
        return $this->hasMany('App\Models\TamerRequirement');
    }

    public function tamerSteps()
    {
        return $this->hasMany('App\Models\TamerStep');
    }
    public function tamerQuestions()
    {
        return $this->hasMany('App\Models\TamerQuestion');
    }
}
