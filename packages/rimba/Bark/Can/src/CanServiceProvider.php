<?php

declare(strict_types=1);

namespace Rimba\Bark\Can;

use Bites\Base\Services\BitesServiceProvider;

class CanServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
