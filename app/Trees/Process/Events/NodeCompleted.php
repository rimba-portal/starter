<?php

declare(strict_types=1);

namespace App\Trees\Process\Events;

use App\Trees\Process\Models\WorkflowNodeInstance;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NodeCompleted
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public WorkflowNodeInstance $nodeInstance
    ) {}
}
