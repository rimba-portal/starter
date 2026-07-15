<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms;

use Bites\Base\Services\BitesServiceProvider;

class DmsServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
