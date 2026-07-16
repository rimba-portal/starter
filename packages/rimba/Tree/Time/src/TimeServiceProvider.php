<?php

declare(strict_types=1);

namespace Rimba\Tree\Time;

use Bites\Base\Services\BitesServiceProvider;
use Rimba\Tree\Time\Actions\DiscoverCalendar;

class TimeServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__ . '/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'rimba');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // app(DiscoverCalendar::class)->execute();
    }
}
