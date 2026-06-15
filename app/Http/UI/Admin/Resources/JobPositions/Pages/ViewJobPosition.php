<?php

namespace App\Http\UI\Admin\Resources\JobPositions\Pages;

use App\Http\UI\Admin\Resources\JobPositions\JobPositionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobPosition extends ViewRecord
{
    protected static string $resource = JobPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
