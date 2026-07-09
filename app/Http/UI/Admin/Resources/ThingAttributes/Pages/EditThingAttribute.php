<?php

namespace App\Http\UI\Admin\Resources\ThingAttributes\Pages;

use App\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditThingAttribute extends EditRecord
{
    protected static string $resource = ThingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            \Filament\Actions\Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn() => redirect()->route('filament.admin.resources.attribute-definitions.thing')),
        ];
    }
}
