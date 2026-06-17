<?php

declare(strict_types=1);

namespace App\Trees\Sync\Actions;

use App\Trees\Sync\Contracts\DataFetcher;
use Illuminate\Support\Facades\DB;

class FetchDatabaseData implements DataFetcher
{
    public function fetch(array $config): array
    {
        return DB::connection($config['connection'])
            ->select($config['query'], $config['bindings'] ?? []);
    }
}
