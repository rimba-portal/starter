<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonAttributes extends ListRecords
{
    protected static string $resource = PersonAttributeResource::class;

    protected static ?string $title = 'Person Attributes';

    protected ?string $subheading = 'Attribute for person resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Person Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.person')),
        ];
    }
}
