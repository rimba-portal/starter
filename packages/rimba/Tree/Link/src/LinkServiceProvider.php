<?php

declare(strict_types=1);

namespace Rimba\Tree\Link;

use Bites\Base\Services\BitesServiceProvider;

class LinkServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
