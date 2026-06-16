<?php

declare(strict_types=1);

namespace App\Trees\Process\Models;

use App\Trees\Process\Builders\WorkflowBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'key',
])]
class Workflow extends Model
{
    public function getRouteKeyName(): string
    {
        return 'key'; // or 'uuid'
    }

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
