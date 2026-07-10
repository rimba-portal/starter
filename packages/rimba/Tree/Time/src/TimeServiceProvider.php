<?php

declare(strict_types=1);

namespace Rimba\Tree\Time;

use App\Services\BitesServiceProvider;
use Rimba\Tree\Time\Actions\DiscoverCalendar;

class TimeServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'rimba');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // app(DiscoverCalendar::class)->execute();
    }
}
