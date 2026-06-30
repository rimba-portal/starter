<?php

declare(strict_types=1);

namespace Rimba\Bark\Branding;

use App\Services\BitesServiceProvider;
use Bites\Attributing\Macros\LockWhenFilledMacro;

class BrandingServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
