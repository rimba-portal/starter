<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam;

use App\Services\BitesServiceProvider;

class EamServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
