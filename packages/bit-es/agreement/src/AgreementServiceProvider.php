<?php

declare(strict_types=1);

namespace Bites\Agreement;

use App\Services\BitesServiceProvider;
use Bites\Attributing\Macros\LockWhenFilledMacro;

class AgreementServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}
