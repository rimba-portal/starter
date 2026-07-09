<?php

namespace App\Http\UI\Admin\Resources\AttributeOptions\Pages;

use App\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeOption extends EditRecord
{
    protected static string $resource = AttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
