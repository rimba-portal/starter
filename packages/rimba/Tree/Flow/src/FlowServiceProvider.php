<?php

declare(strict_types=1);

namespace Rimba\Tree\Flow;

use App\Services\BitesServiceProvider;

class FlowServiceProvider extends BitesServiceProvider
{
    public function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
