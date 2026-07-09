<?php

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use App\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeDefinition extends CreateRecord
{
    protected static string $resource = AttributeDefinitionResource::class;
}
