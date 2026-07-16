<?php

declare(strict_types=1);

namespace Rimba\Tree\Work;

use Bites\Base\Services\BitesServiceProvider;

class WorkServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
