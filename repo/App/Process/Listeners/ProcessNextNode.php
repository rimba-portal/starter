<?php

declare(strict_types=1);

namespace Repo\App\Process\Listeners;

use Repo\App\Process\Actions\ProcessNodeTransition;
use Repo\App\Process\Events\NodeCompleted;

class ProcessNextNode
{
    public function handle(NodeCompleted $event): void
    {
        $nodeInstance = $event->nodeInstance;

        app(ProcessNodeTransition::class)
            ->execute($nodeInstance);
    }
}
