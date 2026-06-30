<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms;

use App\Services\BitesServiceProvider;

class DmsServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
