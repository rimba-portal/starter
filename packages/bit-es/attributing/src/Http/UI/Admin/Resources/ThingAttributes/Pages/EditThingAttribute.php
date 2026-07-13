<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditThingAttribute extends EditRecord
{
    protected static string $resource = ThingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.thing')),
        ];
    }
}
