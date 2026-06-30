<?php

declare(strict_types=1);

namespace Rimba\Tree\Link;

use App\Services\BitesServiceProvider;

class LinkServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
