<?php

declare(strict_types=1);

namespace Rimba\Bark\Branding;

use App\Services\BitesServiceProvider;

class BrandingServiceProvider extends BitesServiceProvider
{
    public function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
