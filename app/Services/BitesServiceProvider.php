<?php

declare(strict_types=1);

namespace App\Services;

use BladeUI\Icons\Factory;
use Illuminate\Support\ServiceProvider;

abstract class BitesServiceProvider extends ServiceProvider
{
    /**
     * Relative paths inside the package.
     */
    protected string $configFile = '';

    protected string $viewsPath = '';

    protected string $iconsPath = '';

    public function register(): void
    {
        $this->registerConfig();
        $this->registerViewPath();
        $this->registerIconPath();
    }

    public function boot(): void
    {
        $this->registerViews();
        $this->registerIcons();
    }

    protected function registerConfig(): void
    {
        if (! $this->configFile || ! file_exists($this->configFile)) {
            return;
        }

        config([
            'bites' => array_replace_recursive(
                config('bites', []),
                require $this->configFile,
            ),
        ]);
    }

    protected function registerViewPath(): void
    {
        if (! $this->viewsPath || ! is_dir($this->viewsPath)) {
            return;
        }

        app()->instance(
            'bites.views',
            array_unique([
                ...app()->bound('bites.views')
                    ? app('bites.views')
                    : [],
                $this->viewsPath,
            ])
        );
    }

    protected function registerIconPath(): void
    {
        if (! $this->iconsPath || ! is_dir($this->iconsPath)) {
            return;
        }

        app()->instance(
            'bites.icons',
            array_unique([
                ...app()->bound('bites.icons')
                    ? app('bites.icons')
                    : [],
                $this->iconsPath,
            ])
        );
    }

    protected function registerViews(): void
    {
        foreach (app('bites.views', []) as $path) {
            $this->loadViewsFrom($path, 'bites');
        }
    }

    protected function registerIcons(): void
    {
        if (! app()->resolved(Factory::class)) {
            $this->callAfterResolving(
                Factory::class,
                fn (Factory $factory) => $this->addIconSet($factory)
            );

            return;
        }

        $this->addIconSet(app(Factory::class));
    }

    protected function addIconSet(Factory $factory): void
    {
        static $registered = false;

        if ($registered) {
            return;
        }

        $registered = true;

        $factory->add('bites', [
            'paths' => app('bites.icons', []),
            'prefix' => 'bites',
        ]);
    }
}
