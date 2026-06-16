<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowEdge extends Model
{
    protected $fillable = [
        'workflow_id',
        'from_node_id',
        'to_node_id',
        'label',
        'condition',
    ];

    protected $casts = [
        'condition' => 'array',
    ];

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
}