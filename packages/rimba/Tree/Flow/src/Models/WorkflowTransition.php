<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'workflow_blueprint_id',
    'from_node_id',
    'to_node_id',
    'name',
    'action',
    'condition',
])]
class WorkflowTransition extends Model
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
            'workflow_blueprint_id' => 'integer',
            'from_node_id' => 'integer',
            'to_node_id' => 'integer',
            'workflow_node_id' => 'integer',
        ];
    }

    public function workflowBlueprint(): BelongsTo
    {
        return $this->belongsTo(WorkflowBlueprint::class);
    }

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }

    public function toNode(): BelongsTo
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}
