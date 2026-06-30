<?php

declare(strict_types=1);

namespace Repo\App\Process\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'workflow_id',
    'status',
    'subject_type',
    'subject_id',
])]
class WorkflowInstance extends Model
{
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
