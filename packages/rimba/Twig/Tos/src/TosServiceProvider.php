<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos;

use Bites\Base\Services\BitesServiceProvider;

class TosServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
