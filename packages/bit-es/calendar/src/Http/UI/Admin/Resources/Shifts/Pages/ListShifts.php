<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
