<?php

namespace App\Trees\Process\Listeners;

use App\Trees\Process\Events\NodeActivated;
use Illuminate\Support\Facades\Log;

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
        Log::info('Notify user: ' . $userId . ' for node ' . $nodeInstance->id);
    }
}
