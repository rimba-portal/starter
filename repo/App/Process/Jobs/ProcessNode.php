<?php

declare(strict_types=1);

namespace Repo\App\Process\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Repo\App\Process\Models\WorkflowNodeInstance;

class ProcessNode implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public WorkflowNodeInstance $nodeInstance
    ) {}

    public function handle(): void
    {
        app(\Repo\App\Process\Actions\ProcessNode::class)
            ->execute($this->nodeInstance);
    }
}
