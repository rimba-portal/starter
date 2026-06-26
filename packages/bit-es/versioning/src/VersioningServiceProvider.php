<?php

declare(strict_types=1);

namespace Bites\Versioning;

use App\Services\BitesServiceProvider;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;
use Bites\Versioning\Traits\HasVersions;
use Filament\Facades\Filament as FacadesFilament;

class VersioningServiceProvider extends BitesServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Intercept the Filament execution pipeline safely before panels render
        FacadesFilament::serving(function (): void {
            foreach (FacadesFilament::getPanels() as $panel) {
                foreach ($panel->getResources() as $resourceClass) {
                    $model = $resourceClass::getModel();

                    // Check if the underlying Eloquent model uses your Rimba Tree / Bites trait
                    if (in_array(HasVersions::class, class_uses_recursive($model))) {
                        // dd($resourceClass::getRelations());
                        // Safely inject your relation manager using Filament's internal pipeline hook
                        // $resourceClass::appendRelationManagers([
                        // VersionsRelationManager::class,
                        // ]);
                    }
                }
            }
        });
    }
}
