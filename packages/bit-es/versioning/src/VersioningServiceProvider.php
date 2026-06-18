<?php

declare(strict_types=1);

namespace Bites\Versioning;

use Illuminate\Support\ServiceProvider;

class VersioningServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bites');
    }

    public function register(): void
    {
        //
    }
}
