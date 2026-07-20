<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

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
