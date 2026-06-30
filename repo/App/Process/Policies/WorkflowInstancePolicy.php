<?php

declare(strict_types=1);

namespace Repo\App\Process\Policies;

use App\Models\User;
use Repo\App\Process\Models\WorkflowInstance;

class WorkflowInstancePolicy
{
    public function view(User $user, WorkflowInstance $instance): bool
    {
        // owner
        if ($instance->subject && method_exists($instance->subject, 'user_id')) {
            return $instance->subject->user_id === $user->id;
        }

        return true;
    }

    public function process(User $user, WorkflowInstance $instance): bool
    {
        return $instance->status === 'running';
    }
}
