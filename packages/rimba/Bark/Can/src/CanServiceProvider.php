<?php

declare(strict_types=1);

namespace Rimba\Bark\Can;

use Bites\Base\Services\BitesServiceProvider;

class CanServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__ . '/../config/bites.php';

    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
