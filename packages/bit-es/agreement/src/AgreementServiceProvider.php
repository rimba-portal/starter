<?php

declare(strict_types=1);

namespace Bites\Agreement;

use App\Services\BitesServiceProvider;

class AgreementServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}
