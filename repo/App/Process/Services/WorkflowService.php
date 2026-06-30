<?php

declare(strict_types=1);

namespace Repo\App\Process\Services;

use Illuminate\Support\Collection;
use Repo\App\Process\Actions\CreateWorkflowInstance;
use Repo\App\Process\Actions\ProcessWorkflow;
use Repo\App\Process\Events\WorkflowCompleted;
use Repo\App\Process\Events\WorkflowStarted;
use Repo\App\Process\Models\Workflow;
use Repo\App\Process\Models\WorkflowInstance;
use Repo\App\Process\Models\WorkflowNode;

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
