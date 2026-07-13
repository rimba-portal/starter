<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeDefinition extends CreateRecord
{
    protected static string $resource = AttributeDefinitionResource::class;
}
