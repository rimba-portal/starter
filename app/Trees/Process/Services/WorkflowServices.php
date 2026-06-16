<?php

declare(strict_types=1);

namespace App\Trees\Process\Services;

use App\Trees\Process\Actions\CreateWorkflowInstance;
use App\Trees\Process\Actions\ProcessWorkflow;
use App\Trees\Process\Events\WorkflowCompleted;
use App\Trees\Process\Events\WorkflowStarted;
use App\Trees\Process\Models\Workflow;
use App\Trees\Process\Models\WorkflowInstance;
use App\Trees\Process\Models\WorkflowNode;
use Illuminate\Support\Collection;

class WorkflowService
{
    public function startWorkflow(Workflow $workflow, $subject): WorkflowInstance
    {
        $workflowInstance = app(CreateWorkflowInstance::class)
            ->execute($workflow->id, $subject);

        event(new WorkflowStarted($workflowInstance));

        return $workflowInstance;
    }

    public function processWorkflow(WorkflowInstance $instance): void
    {
        app(ProcessWorkflow::class)
            ->execute($instance);
    }

    public function getStartNodesForRoles(array $roles): Collection
    {
        return WorkflowNode::query()
            ->where('type', 'start')
            ->whereIn('role_name', $roles)
            ->get();
    }

    public function completeWorkflow(WorkflowInstance $instance): void
    {
        $instance->update(['status' => 'completed']);

        event(new WorkflowCompleted($instance));
    }
}
