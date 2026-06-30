<?php

declare(strict_types=1);

namespace Rimba\Tree;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Rimba\Tree\Flow\WorkflowNode;

#[Fillable([
    'workflow_node_id',
    'attachable_id',
    'attachable_type',
])]
class WorkflowNodeAttachable extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'workflow_node_id' => 'integer',
        ];
    }

    public function workflowNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}
