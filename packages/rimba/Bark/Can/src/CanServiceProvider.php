<?php

declare(strict_types=1);

namespace Rimba\Bark\Can;

use App\Services\BitesServiceProvider;

class CanServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
