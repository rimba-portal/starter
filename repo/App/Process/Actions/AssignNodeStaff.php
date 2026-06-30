<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowNodeInstance;

class AssignNodeStaff
{
    public function execute(WorkflowNodeInstance $nodeInstance, int $staffId): WorkflowNodeInstance
    {
        $data = $nodeInstance->data ?? [];

        $data['assigned_staff_id'] = $staffId;

        $nodeInstance->update([
            'data' => $data,
        ]);

        return $nodeInstance;
    }
}
