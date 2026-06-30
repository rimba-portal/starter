<?php

declare(strict_types=1);

namespace Rimba\Twig\Lcs;

use App\Services\BitesServiceProvider;

class LcsServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
