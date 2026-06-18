<?php

declare(strict_types=1);

namespace App\Providers;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

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
            $factory->add('rimba', [
                'path' => resource_path('svg'),
                'prefix' => 'bites',
            ]);
        });
    }
}
