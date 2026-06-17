<?php

declare(strict_types=1);

namespace App\Trees\Sync\Services;

use App\Actions\PutFingerPrint;
use App\Trees\Sync\Actions\FetchDatabaseData;
use App\Trees\Sync\Actions\FetchRestData;
use App\Trees\Sync\Models\ApiConfig;
use App\Trees\Sync\Models\ApiData;

class FetchService
{
    public function fetch(ApiConfig $config): void
    {
        $fetcher = match ($config->source_type) {
            'rest' => new FetchRestData,
            'database' => new FetchDatabaseData,
        };

        $data = $fetcher->fetch($config->source_config);

        $items = data_get($data, $config->data_path ?? 'data', $data);

        // foreach ($items as $item) {
        ApiData::firstOrCreate(
            [
                'api_config_id' => $config->id,
                'fingerprint' => PutFingerPrint::make((array) $items),
            ],
            [
                'payload' => (array) $items,
                'status' => 'pending',
            ]
        );
        // }
    }
}
