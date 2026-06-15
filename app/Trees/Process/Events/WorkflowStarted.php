<?php

namespace App\Trees\Process\Events;

use App\Trees\Process\Models\WorkflowInstance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WorkflowStarted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public WorkflowInstance $workflowInstance
    ) {}
}