<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowInstance extends Model
{
    protected $fillable = [
        'workflow_id',
        'status',
        'subject_type',
        'subject_id',
    ];

    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function nodeInstances()
    {
        return $this->hasMany(WorkflowNodeInstance::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }
}