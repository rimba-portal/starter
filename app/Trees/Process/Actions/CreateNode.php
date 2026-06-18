<?php

declare(strict_types=1);

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowNode;

class CreateNode
{
    public function execute(array $data): WorkflowNode
    {
        return WorkflowNode::create([
            'workflow_id' => $data['workflow_id'],
            'name' => $data['name'],
            'type' => $data['type'],
            'role_name' => $data['role_name'] ?? null,
            'config' => $data['config'] ?? [],
        ]);
    }
}
