<?php

declare(strict_types=1);

namespace Rimba\Twig\Lcs;

use App\Services\BitesServiceProvider;

class LcsServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
