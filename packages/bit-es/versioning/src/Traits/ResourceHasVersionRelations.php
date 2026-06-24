<?php

declare(strict_types=1);

namespace Bites\Versioning\Traits;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;

trait ResourceHasVersionRelations
{
     public static function getRelations(): array
    {
        // Fallback check to avoid conflicts if parent or local defines relations
        $localRelations = method_exists(self::class, 'getRelations') ? self::getRelations() : [];
        
        return array_merge($localRelations, [
            VersionsRelationManager::class,
        ]);
    }
}
