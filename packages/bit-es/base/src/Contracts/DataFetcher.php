<?php

declare(strict_types=1);

namespace Bites\Base\Contracts;

interface DataFetcher
{
    public function fetch(array $config): array;
}
