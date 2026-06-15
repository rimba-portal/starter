<?php

namespace App\Trees\Process\Jobs;

use App\Trees\Process\Models\WorkflowInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessWorkflow implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public WorkflowInstance $workflowInstance
    ) {}

    public function handle(): void
    {
        app(\App\Trees\Process\Actions\ProcessWorkflow::class)
            ->execute($this->workflowInstance);
    }
}