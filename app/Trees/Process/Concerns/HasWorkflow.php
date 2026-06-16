<?php

declare(strict_types=1);

namespace App\Trees\Process\Concerns;

use App\Trees\Process\Actions\CreateWorkflowInstance;
use App\Trees\Process\Models\WorkflowInstance;

trait HasWorkflow
{
    public function workflowInstances()
    {
        return $this->morphMany(WorkflowInstance::class, 'subject');
    }

    public function activeWorkflowInstances()
    {
        return $this->workflowInstances()
            ->where('status', 'running');
    }

    public function completedWorkflowInstances()
    {
        return $this->workflowInstances()
            ->where('status', 'completed');
    }

    public function startWorkflow(int $workflowId, array $attributes = []): WorkflowInstance
    {
        return app(CreateWorkflowInstance::class)
            ->execute($workflowId, $this, $attributes);
    }
}
