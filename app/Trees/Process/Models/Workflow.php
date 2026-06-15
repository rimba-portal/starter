<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Model;
use App\Trees\Process\Builders\WorkflowBuilder;

class Workflow extends Model
{
    protected $fillable = [
        'name',
        'key',
    ];

    public function nodes()
    {
        return $this->hasMany(WorkflowNode::class);
    }

    public function edges()
    {
        return $this->hasMany(WorkflowEdge::class);
    }

    public function instances()
    {
        return $this->hasMany(WorkflowInstance::class);
    }

    public function newEloquentBuilder($query)
    {
        return new WorkflowBuilder($query);
    }
}
