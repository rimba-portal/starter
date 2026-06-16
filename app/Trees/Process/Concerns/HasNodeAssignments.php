<?php

declare(strict_types=1);

namespace App\Trees\Process\Concerns;

use App\Trees\Process\Models\WorkflowNodeInstance;

trait HasNodeAssignments
{
    public function assignedNodeInstances()
    {
        return WorkflowNodeInstance::query()
            ->whereJsonContains('data->assigned_staff_id', $this->staff->id);

    }

    public function pendingNodeInstances()
    {
        return $this->assignedNodeInstances()
            ->where('status', 'active');
    }
}
