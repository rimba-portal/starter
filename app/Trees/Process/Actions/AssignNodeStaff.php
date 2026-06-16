<?php

declare(strict_types=1);

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowNodeInstance;

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
