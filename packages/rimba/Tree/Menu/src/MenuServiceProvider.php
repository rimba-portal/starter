<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu;

use Bites\Base\Services\BitesServiceProvider;

class MenuServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
