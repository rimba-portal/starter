<?php

declare(strict_types=1);

namespace Rimba\Bark\Who;

use Bites\Base\Services\BitesServiceProvider;

class WhoServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__ . '/../config/bites.php';

    protected string $viewsPath = __DIR__ . '/../resources/views';
    
    protected string $iconsPath = __DIR__ . '/../resources/svg';

    protected function bootPackage(): void
    {
        // dd(app('view')->getFinder()->getHints());
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
