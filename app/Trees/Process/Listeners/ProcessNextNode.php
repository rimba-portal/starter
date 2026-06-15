<?php

namespace App\Trees\Process\Listeners;

use App\Trees\Process\Events\NodeCompleted;

class ProcessNextNode
{
    public function handle(NodeCompleted $event): void
    {
        $nodeInstance = $event->nodeInstance;

        app(\App\Trees\Process\Actions\ProcessNodeTransition::class)
            ->execute($nodeInstance);
    }
}
