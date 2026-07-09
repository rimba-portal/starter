<?php

namespace App\Http\UI\Admin\Resources\PersonAttributes\Pages;

use App\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonAttribute extends EditRecord
{
    protected static string $resource = PersonAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            \Filament\Actions\Action::make('definition')
                ->tooltip('Definitions for Person Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn() => redirect()->route('filament.admin.resources.attribute-definitions.person')),
        ];
    }
}
