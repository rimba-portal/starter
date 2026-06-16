<?php

declare(strict_types=1);

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowNodeInstance;

class ProcessNode
{
    public function execute(WorkflowNodeInstance $nodeInstance): void
    {
        // ✅ business logic placeholder (approval, form etc.)

        // mark as completed
        $nodeInstance->update(['status' => 'completed']);

        // move to next nodes
        app(ProcessNodeTransition::class)->execute($nodeInstance);
    }
}
