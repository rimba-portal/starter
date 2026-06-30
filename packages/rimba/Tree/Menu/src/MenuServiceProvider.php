<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu;

use App\Services\BitesServiceProvider;
use Bites\Attributing\Macros\LockWhenFilledMacro;

class MenuServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
