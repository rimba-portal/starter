<?php

declare(strict_types=1);

namespace Rimba\Bark\Trail;

use App\Services\BitesServiceProvider;

class TrailServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
