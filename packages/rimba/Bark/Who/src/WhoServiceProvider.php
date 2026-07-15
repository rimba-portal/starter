<?php

declare(strict_types=1);

namespace Rimba\Bark\Who;

use Bites\Base\Services\BitesServiceProvider;

class WhoServiceProvider extends BitesServiceProvider
{
    protected string $viewsPath = __DIR__.'/../resources/views';

    protected function bootPackage(): void
    {
        // dd(app('view')->getFinder()->getHints());
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
