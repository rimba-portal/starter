<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowInstance;
use Repo\App\Process\Models\WorkflowNode;
use Repo\App\Process\Models\WorkflowNodeInstance;

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
