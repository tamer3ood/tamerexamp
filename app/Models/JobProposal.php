<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobProposal extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function job()
    {
        return $this->belongsTo('App\Models\Job', 'job_id');
    }
    public function talent()
    {
        return $this->belongsTo('App\Models\User', 'talent_id');
    }
}
