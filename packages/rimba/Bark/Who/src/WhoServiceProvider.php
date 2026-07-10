<?php

declare(strict_types=1);

namespace Rimba\Bark\Who;

use App\Services\BitesServiceProvider;

class WhoServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
