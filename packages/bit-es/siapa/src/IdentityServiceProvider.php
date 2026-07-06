<?php

declare(strict_types=1);

namespace Bites\Identity;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/identity.php', 'identity');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bites-identity');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/identity.php' => config_path('identity.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/assets/models' => public_path('models'),
        ], 'assets');

    }
}
