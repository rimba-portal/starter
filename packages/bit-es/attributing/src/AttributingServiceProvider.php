<?php

declare(strict_types=1);

namespace Bites\Attributing;

use App\Services\BitesServiceProvider;
use Filament\Facades\Flament;
use Filament\Facades\Filament as FacadesFilament;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;
use Bites\Versioning\Traits\HasVersions;

class AttributingServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        \Bites\Attributing\Macros\LockWhenFilledMacro::register();
    }
}
