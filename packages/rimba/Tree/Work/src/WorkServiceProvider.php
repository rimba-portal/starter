<?php

declare(strict_types=1);

namespace Rimba\Tree\Work;

use App\Services\BitesServiceProvider;

class WorkServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
