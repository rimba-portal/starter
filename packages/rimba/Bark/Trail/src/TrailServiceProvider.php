<?php

declare(strict_types=1);

namespace Rimba\Bark\Trail;

use App\Services\BitesServiceProvider;

class TrailServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
