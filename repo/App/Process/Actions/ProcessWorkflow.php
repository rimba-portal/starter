<?php

declare(strict_types=1);

namespace Repo\App\Process\Actions;

use Repo\App\Process\Models\WorkflowInstance;

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
