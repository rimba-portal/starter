<?php

declare(strict_types=1);

namespace Bites\FloorPlan;

use App\Services\BitesServiceProvider;
use Bites\FloorPlan\Actions\DiscoverFloorPlan;

class FloorPlanServiceProvider extends BitesServiceProvider
{
    protected string $configFile =
        __DIR__.'/../config/bites.php';

    protected string $viewsPath =
        __DIR__.'/../resources/views';

    protected string $iconsPath =
        __DIR__.'/../resources/svg';

    protected function registerPackage(): void
    {
        //
    }

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        app(DiscoverFloorPlan::class)->execute();
    }
}
