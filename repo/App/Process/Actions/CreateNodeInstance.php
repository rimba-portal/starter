<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowNodeInstance;

class CreateNodeInstance
{
    public function execute(array $data): WorkflowNodeInstance
    {
        return WorkflowNodeInstance::create([
            'workflow_instance_id' => $data['workflow_instance_id'],
            'node_id' => $data['node_id'],
            'status' => $data['status'] ?? 'pending',
            'data' => $data['data'] ?? null,
        ]);
    }
}
