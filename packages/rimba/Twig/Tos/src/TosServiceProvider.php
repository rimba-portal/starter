<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos;

use App\Services\BitesServiceProvider;

class TosServiceProvider extends BitesServiceProvider
{
    public function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
