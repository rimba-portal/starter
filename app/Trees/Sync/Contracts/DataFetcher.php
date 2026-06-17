<?php

declare(strict_types=1);

namespace App\Trees\Sync\Contracts;

interface DataFetcher
{
    public function fetch(array $config): array;
}
