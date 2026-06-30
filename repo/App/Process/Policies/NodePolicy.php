<?php

declare(strict_types=1);

namespace Repo\App\Process\Policies;

use App\Models\User;
use Repo\App\Process\Models\WorkflowNode;

class NodePolicy
{
    public function execute(User $user, WorkflowNode $node): bool
    {
        if (! $node->role_name) {
            return true;
        }

        return $user->hasRole($node->role_name);
    }
}
