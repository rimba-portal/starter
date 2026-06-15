<?php

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowNode;

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