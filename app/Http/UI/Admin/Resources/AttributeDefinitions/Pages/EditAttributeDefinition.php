<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use App\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeDefinition extends EditRecord
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
