<?php

declare(strict_types=1);

namespace Repo\App\Process\Models;

use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Repo\App\Process\Builders\WorkflowBuilder;

#[Fillable([
    'name',
    'key',
])]
class Workflow extends Model
{
    use HasVersions;

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
