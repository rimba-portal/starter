<?php

declare(strict_types=1);

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'workflow_id',
    'from_node_id',
    'to_node_id',
    'label',
    'condition',
])]
class WorkflowEdge extends Model
{
    public function workflow()
    {
        return $this->belongsTo(Workflow::class);
    }

    public function fromNode()
    {
        return $this->belongsTo(WorkflowNode::class, 'from_node_id');
    }

    public function toNode()
    {
        return $this->belongsTo(WorkflowNode::class, 'to_node_id');
    }

    protected function casts(): array
    {
        return [
            'condition' => 'array',
        ];
    }
}
