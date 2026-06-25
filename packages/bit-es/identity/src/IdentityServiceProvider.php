<?php

namespace Rimba\Identity;

use Illuminate\Support\ServiceProvider;
use Rimba\Identity\Managers\IdentityManager;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/identity.php',
            'identity'
        );

        $this->app->singleton(
            IdentityManager::class,
            function () {

                $manager = new IdentityManager();

                foreach (
                    config('identity.drivers')
                    as $name => $driver
                ) {

                    $manager->extend(
                        $name,
                        $driver
                    );
                }

                return $manager;
            }
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/identity.php'
                => config_path('identity.php'),
        ], 'identity-config');

        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );
    }
}