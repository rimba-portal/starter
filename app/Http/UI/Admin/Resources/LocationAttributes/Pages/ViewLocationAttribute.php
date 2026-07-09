<?php

namespace App\Http\UI\Admin\Resources\LocationAttributes\Pages;

use App\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLocationAttribute extends ViewRecord
{
    protected static string $resource = LocationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            \Filament\Actions\Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn() => redirect()->route('filament.admin.resources.attribute-definitions.location')),

        ];
    }
}
