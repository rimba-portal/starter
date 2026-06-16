<?php

declare(strict_types=1);

namespace App\Trees\Process\Actions;

use App\Trees\Process\Models\WorkflowInstance;

class ProcessWorkflow
{
    public function execute(WorkflowInstance $instance): void
    {
        $activeNodes = $instance->nodeInstances()
            ->where('status', 'active')
            ->get();

        foreach ($activeNodes as $activeNode) {
            app(ProcessNode::class)->execute($activeNode);
        }
    }
}
