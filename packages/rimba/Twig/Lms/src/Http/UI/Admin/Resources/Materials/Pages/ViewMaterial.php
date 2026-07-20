<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMaterial extends ViewRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
