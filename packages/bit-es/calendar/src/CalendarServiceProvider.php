<?php

declare(strict_types=1);

namespace Bites\Calendar;

use App\Services\BitesServiceProvider;
use Bites\Calendar\Actions\DiscoverCalendar;

class CalendarServiceProvider extends BitesServiceProvider
{
    protected string $configFile =
        __DIR__.'/../config/bites.php';

    protected string $viewsPath =
        __DIR__.'/../resources/views';

    protected string $iconsPath =
        __DIR__.'/../resources/svg';

    public function registerPackage(): void
    {
        //
    }

    public function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        app(DiscoverCalendar::class)->execute();
    }
}
