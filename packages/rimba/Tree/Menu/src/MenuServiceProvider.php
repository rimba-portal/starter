<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu;

use Bites\Base\Services\BitesServiceProvider;

class MenuServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
