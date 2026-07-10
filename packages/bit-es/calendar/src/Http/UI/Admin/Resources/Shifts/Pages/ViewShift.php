<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShift extends ViewRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
