<?php

namespace App\Http\UI\Admin\Resources\ThingAttributes\Pages;

use App\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListThingAttributes extends ListRecords
{
    protected static string $resource = ThingAttributeResource::class;

    protected static ?string $title = 'Thing Attributes';

    protected ?string $subheading = 'Attribute for physical item resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            \Filament\Actions\Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn() => redirect()->route('filament.admin.resources.attribute-definitions.thing')),

        ];
    }
}
