<?php

declare(strict_types=1);

namespace Repo\App\Process\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'workflow_instance_id',
    'workflow_node_id',
    'status',
    'data',
])]
class WorkflowNodeInstance extends Model
{
    public function workflowInstance()
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function node()
    {
        return $this->belongsTo(WorkflowNode::class);
    }

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
}
