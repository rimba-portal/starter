<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ThingAttributes\Pages;

use App\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThingAttributes extends ListRecords
{
    protected static string $resource = ThingAttributeResource::class;

    protected static ?string $title = 'Thing Attributes';

    protected ?string $subheading = 'Attribute for physical item resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.thing')),

        ];
    }
}
