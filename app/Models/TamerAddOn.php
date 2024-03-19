<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamerAddOn extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function CategoryElement()
    {
        return $this->belongsTo('App\Models\CategoryElement', 'category_eleserv_id');
    }

    public function CategoryService()
    {
        return $this->belongsTo('App\Models\CategoryService', 'category_eleserv_id');
    }
}
