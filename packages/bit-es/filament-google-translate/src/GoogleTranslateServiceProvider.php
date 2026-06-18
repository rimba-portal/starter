<?php

declare(strict_types=1);

namespace Bites\GoogleTranslate;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;
use Bites\GoogleTranslate\Actions\RegisterLanguageSwitcher;

class GoogleTranslateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bites');

        app(RegisterLanguageSwitcher::class)->execute();

        $this->callAfterResolving(Factory::class, function (Factory $factory): void {
            $factory->add('bites', [
                'path' => __DIR__ . '/../resources/svg',
                'prefix' => 'flag',
            ]);
            // dump($factory);
        });
    }
    public function register(): void
    {
        //
    }
}
