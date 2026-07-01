<?php

namespace Bites\Identity;

use Illuminate\Support\ServiceProvider;
use Filament\Panel;

class IdentityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/identity.php', 'identity');
    }

    public function boot()
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

        // if (config('identity.auto_register', true)) {
        //     \Filament\Facades\Filament::serving(function () {
        //         \Filament\Facades\Filament::registerPanelModification(function (Panel $panel) {
        //             $panel
        //                 ->login(Pages\Auth\Login::class)
        //                 ->registration(Pages\Auth\Register::class)
        //                 ->passwordReset(Pages\Auth\ForgotPassword::class)
        //                 ->profile(Pages\Profile::class)
        //                 ->multiFactorAuthentication([
        //                     \Filament\Auth\MultiFactor\App\AppAuthentication::make()
        //                         ->brandName(config('app.name'))
        //                         ->recoverable(),
        //                 ])
        //                 ->middleware([
        //                     \Bites\Identity\Http\Middleware\EnsureSetupIsComplete::class,
        //                 ]);
        //         });
        //     });
        // }
    }
}