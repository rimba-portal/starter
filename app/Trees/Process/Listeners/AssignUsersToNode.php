<?php

namespace App\Trees\Process\Listeners;

use App\Trees\Process\Events\NodeActivated;
use App\Models\User;

class AssignUsersToNode
{
    public function handle(NodeActivated $event): void
    {
        $nodeInstance = $event->nodeInstance;
        $node = $nodeInstance->node;
        $subject = $nodeInstance->workflowInstance->subject;

        $staffCollection = app(\App\Trees\Process\Support\NodeResolver::class)
            ->resolveStaff($node, $subject);

        $staff = $staffCollection->first();

        if (! $staff) {
            return;
        }

        app(\App\Trees\Process\Actions\AssignNodeStaff::class)
            ->execute($nodeInstance, $staff->id);
    }
}
