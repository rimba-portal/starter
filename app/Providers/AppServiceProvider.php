<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\DiscoverBranding;
use App\Services\AuthOrchestrator;
use App\Services\BuiltInAuthService;
use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(function ($app): AuthOrchestrator {
            return new AuthOrchestrator([
                // $app->make(\Rimba\LdapAuth\Services\LdapAuthService::class),
                $app->make(BuiltInAuthService::class),
            ]);
        });
        app(DiscoverBranding::class)->execute();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('rimba', [
                'path' => resource_path('svg'),
                'prefix' => 'rimba',
            ]);
        });
    }
}
