<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationAttributes extends ListRecords
{
    protected static string $resource = LocationAttributeResource::class;

    protected static ?string $title = 'Location Attributes';

    protected ?string $subheading = 'Attribute for location resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.location')),
        ];
    }
}
