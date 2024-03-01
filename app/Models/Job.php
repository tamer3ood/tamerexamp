<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;

class Job extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value, '-') . time();


        // Str::slug('Laravel 5 Framework', '-');

    }
    public function jobCategoryAttributeItems()
    {
        return $this->belongsToMany('App\Models\CategoryAttributeItem', 'job_category_attribute_item', 'job_id', 'category_attribute_item_id');

    }
    public function jobCategoryServices()
    {
        return $this->belongsToMany('App\Models\CategoryService', 'job_category_service', 'job_id', 'category_service_id');

    }
    public function jobSkills()
    {
        return $this->belongsToMany('App\Models\Skill', 'job_skill', 'job_id', 'skill_id');

    }

    public function jobCategoryElements()
    {
        return $this->hasMany('App\Models\JobCategoryElement', 'job_id');
    }

    public function jobFiles()
    {
        return $this->hasMany('App\Models\JobFile', 'job_id');
    }

    public function proposals()
    {
        return $this->hasMany('App\Models\JobProposal', 'job_id');
    }

}
