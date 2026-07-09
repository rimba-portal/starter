<?php

namespace App\Http\UI\Admin\Resources\LocationAttributes\Pages;

use App\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationAttributes extends ListRecords
{
    protected static string $resource = LocationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn() => redirect()->route('filament.admin.resources.attribute-definitions.location')),
        ];
    }
}
