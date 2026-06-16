<?php

declare(strict_types=1);

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowEdge;

class CreateEdge
{
    public function execute(array $data): WorkflowEdge
    {
        return WorkflowEdge::create([
            'workflow_id' => $data['workflow_id'],
            'from_node_id' => $data['from_node_id'],
            'to_node_id' => $data['to_node_id'],
            'label' => $data['label'] ?? null,
            'condition' => $data['condition'] ?? null,
        ]);
    }
}
