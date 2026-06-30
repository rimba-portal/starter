<?php

declare(strict_types=1);

namespace Repo\App\Process\Listeners;

use Illuminate\Support\Facades\Log;
use Repo\App\Process\Events\NodeActivated;

class SendNodeNotification
{
    public function handle(NodeActivated $event): void
    {
        $nodeInstance = $event->nodeInstance;

        $userId = data_get($nodeInstance->data, 'assigned_user_id');

        if (! $userId) {
            return;
        }

        // Replace with your NotificationService later
        Log::info('Notify user: '.$userId.' for node '.$nodeInstance->id);
    }
}
