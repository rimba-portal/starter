<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Staff\Pages;

use App\Http\UI\Admin\Resources\Staff\StaffResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStaff extends ViewRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
