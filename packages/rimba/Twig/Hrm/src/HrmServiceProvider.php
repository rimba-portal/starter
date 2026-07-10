<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm;

use App\Services\BitesServiceProvider;

class HrmServiceProvider extends BitesServiceProvider
{
    public function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
