<?php

declare(strict_types=1);

namespace App\Trees\Sync\Observers;

use App\Trees\Sync\Models\ApiData;
use App\Trees\Sync\Jobs\ProcessApiDataJob;
use App\Trees\Sync\Services\ProcessingService;

class ApiDataObserver
{
    public function created(ApiData $data): void
    {
        app(ProcessingService::class)->process($data);
    }

    public function updated(ApiData $data): void
    {

        if (in_array($data->status, ['processed', 'failed'], true)) {
            return;
        }

        // ✅ Respect config: async (queue) OR sync (immediate)
        if (config('bites.sync.queue', true)) {
            ProcessApiDataJob::dispatch($data);

            return;
        }

        // ✅ Sync processing fallback
        app(ProcessingService::class)->process($data);
    }
}
