<?php

namespace App\Trees\Process\Jobs;

use App\Trees\Process\Models\WorkflowNodeInstance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNode implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        public WorkflowNodeInstance $nodeInstance
    ) {}

    public function handle(): void
    {
        app(\App\Trees\Process\Actions\ProcessNode::class)
            ->execute($this->nodeInstance);
    }
}