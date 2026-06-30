<?php

declare(strict_types=1);

namespace Repo\App\Process\Listeners;

use Repo\App\Process\Actions\AssignNodeStaff;
use Repo\App\Process\Events\NodeActivated;
use Repo\App\Process\Support\NodeResolver;

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
