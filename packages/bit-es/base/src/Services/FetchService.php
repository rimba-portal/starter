<?php

declare(strict_types=1);

namespace Bites\Base\Services;

use Bites\Base\Actions\FetchDatabaseData;
use Bites\Base\Actions\FetchRestData;
use Bites\Base\Actions\PutFingerPrint;
use Bites\Base\Models\ApiConfig;
use Bites\Base\Models\ApiData;

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
