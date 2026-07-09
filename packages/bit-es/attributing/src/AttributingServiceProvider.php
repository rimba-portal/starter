<?php

declare(strict_types=1);

namespace Bites\Attributing;

use App\Services\BitesServiceProvider;
use Bites\Attributing\Macros\LockWhenFilledMacro;

class AttributingServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/attributes.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        LockWhenFilledMacro::register();
        // dd(config('bites.groups.person'));
    }
}
