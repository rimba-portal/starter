<?php

declare(strict_types=1);

namespace App\Trees\Process\Listeners;

use App\Trees\Process\Actions\AssignNodeStaff;
use App\Trees\Process\Events\NodeActivated;
use App\Trees\Process\Support\NodeResolver;

class AssignUsersToNode
{
    public function handle(NodeActivated $event): void
    {
        $nodeInstance = $event->nodeInstance;
        $node = $nodeInstance->node;
        $subject = $nodeInstance->workflowInstance->subject;

        $staffCollection = app(NodeResolver::class)
            ->resolveStaff($node, $subject);

        $staff = $staffCollection->first();

        if (! $staff) {
            return;
        }

        app(AssignNodeStaff::class)
            ->execute($nodeInstance, $staff->id);
    }
}
