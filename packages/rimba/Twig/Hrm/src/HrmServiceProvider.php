<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm;

use Bites\Base\Services\BitesServiceProvider;

class HrmServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__ . '/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
