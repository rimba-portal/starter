<?php

namespace App\Trees\Process\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowNodeInstance extends Model
{
    protected $fillable = [
        'workflow_instance_id',
        'workflow_node_id',
        'status',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function workflowInstance()
    {
        return $this->belongsTo(WorkflowInstance::class);
    }

    public function node()
    {
        return $this->belongsTo(WorkflowNode::class);
    }
}