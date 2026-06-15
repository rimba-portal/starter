<?php

namespace App\Trees\Process\Events;

use App\Trees\Process\Models\WorkflowNodeInstance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NodeActivated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public WorkflowNodeInstance $nodeInstance
    ) {}
}