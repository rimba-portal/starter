<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use BladeUI\Icons\Factory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('bites', [
                'path' => resource_path('svg'),
                'prefix' => 'bites',
            ]);
        });
    }
}
