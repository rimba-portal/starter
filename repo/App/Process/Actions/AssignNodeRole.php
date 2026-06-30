<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowNode;

class AssignNodeRole
{
    public function execute(WorkflowNode $node, string $roleName): WorkflowNode
    {
        $node->update([
            'role_name' => $roleName,
        ]);

        return $node;
    }
}
