<?php

declare(strict_types=1);

namespace Bites\Attributing\Support;

use Bites\Attributing\Http\UI\RelationManagers\PersonAttributesRelationManager;
use Bites\Attributing\Http\UI\RelationManagers\PlaceAttributesRelationManager;
use Bites\Attributing\Http\UI\RelationManagers\ThingAttributesRelationManager;
use Bites\Attributing\Traits\HasPersonAttributes;
use Bites\Attributing\Traits\HasPlaceAttributes;
use Bites\Attributing\Traits\HasThingAttributes;

final class HasAttributeRelationManagers
{
    /**
     * @return array<class-string>
     */
    public static function forModel(string $modelClass): array
    {
        $traits = class_uses_recursive($modelClass);

        $relations = [];

        if (in_array(HasPersonAttributes::class, $traits, true)) {
            $relations[] = PersonAttributesRelationManager::class;
        }

        if (in_array(HasThingAttributes::class, $traits, true)) {
            $relations[] = ThingAttributesRelationManager::class;
        }

        if (in_array(HasPlaceAttributes::class, $traits, true)) {
            $relations[] = PlaceAttributesRelationManager::class;
        }

        return $relations;
    }
}
