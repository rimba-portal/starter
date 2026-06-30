<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowNode;

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
