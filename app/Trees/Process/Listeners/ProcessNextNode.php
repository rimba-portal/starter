<?php

declare(strict_types=1);

namespace App\Trees\Process\Listeners;

use App\Trees\Process\Actions\ProcessNodeTransition;
use App\Trees\Process\Events\NodeCompleted;

class ProcessNextNode
{
    public function handle(NodeCompleted $event): void
    {
        $nodeInstance = $event->nodeInstance;

        app(ProcessNodeTransition::class)
            ->execute($nodeInstance);
    }
}
