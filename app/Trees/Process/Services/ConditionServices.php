<?php

namespace App\Trees\Process\Services;

use App\Trees\Process\Models\Workflow;
use App\Trees\Process\Models\WorkflowInstance;
use App\Trees\Process\Models\Node;
use Illuminate\Support\Collection;

class WorkflowService
{
    public function startWorkflow(Workflow $workflow, $subject): WorkflowInstance
    {
        $instance = app(\App\Trees\Process\Actions\CreateWorkflowInstance::class)
            ->execute($workflow->id, $subject);

        event(new \App\Trees\Process\Events\WorkflowStarted($instance));

        return $instance;
    }

    public function processWorkflow(WorkflowInstance $instance): void
    {
        app(\App\Trees\Process\Actions\ProcessWorkflow::class)
            ->execute($instance);
    }

    public function getStartNodesForRoles(array $roles): Collection
    {
        return Node::query()
            ->where('type', 'start')
            ->whereIn('role_name', $roles)
            ->get();
    }

    public function completeWorkflow(WorkflowInstance $instance): void
    {
        $instance->update(['status' => 'completed']);

        event(new \App\Trees\Process\Events\WorkflowCompleted($instance));
    }
}
