<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
