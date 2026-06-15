<?php

namespace App\Trees\Process\Policies;

use App\Models\User;
use App\Trees\Process\Models\WorkflowInstance;

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