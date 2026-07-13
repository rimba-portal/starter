<?php

declare(strict_types=1);

namespace Bites\Base\Actions;

use Bites\Base\Contracts\DataFetcher;
use Illuminate\Support\Facades\Http;

class FetchRestData implements DataFetcher
{
    public function fetch(array $config): array
    {
        return Http::withHeaders($config['headers'] ?? [])
            ->get($config['url'], $config['query'] ?? [])
            ->json();
    }
}
