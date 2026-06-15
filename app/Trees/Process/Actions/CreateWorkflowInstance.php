<?php

namespace App\Trees\Process\Actions;

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowInstance;
use App\Trees\Process\Models\WorkflowNodeInstance;
use App\Trees\Process\Models\WorkflowNode;

class CreateWorkflowInstance
{
    public function execute(int $workflowId, $subject, array $attributes = []): WorkflowInstance
    {
        $instance = WorkflowInstance::create([
            'workflow_id' => $workflowId,
            'status' => 'running',
            'subject_type' => get_class($subject),
            'subject_id' => $subject->getKey(),
        ]);

        // ✅ find start node
        $startNode = WorkflowNode::where('workflow_id', $workflowId)
            ->where('type', 'start')
            ->firstOrFail();

        // ✅ create first node instance
        WorkflowNodeInstance::create([
            'workflow_instance_id' => $instance->id,
            'node_id' => $startNode->id,
            'status' => 'active',
        ]);

        return $instance;
    }
}