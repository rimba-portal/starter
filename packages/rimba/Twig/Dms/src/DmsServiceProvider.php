<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms;

use Bites\Base\Services\BitesServiceProvider;

class DmsServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
