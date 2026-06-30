<?php

declare(strict_types=1);

namespace Repo\App\Process\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Repo\App\Process\Models\WorkflowNodeInstance;

class NodeActivated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public WorkflowNodeInstance $nodeInstance
    ) {}
}
