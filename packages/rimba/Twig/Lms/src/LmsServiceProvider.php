<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms;

use App\Services\BitesServiceProvider;

class LmsServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
