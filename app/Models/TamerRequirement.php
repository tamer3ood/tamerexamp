<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TamerRequirement extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function forMultipleChoices()
    {
        return $this->hasMany('App\Models\TamerRequirementForMultipleChoice', 'tamer_requirement_id');
    }
}
