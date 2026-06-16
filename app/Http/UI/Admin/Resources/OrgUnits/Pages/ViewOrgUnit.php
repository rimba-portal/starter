<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits\Pages;

use App\Http\UI\Admin\Resources\OrgUnits\OrgUnitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrgUnit extends ViewRecord
{
    protected static string $resource = OrgUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
