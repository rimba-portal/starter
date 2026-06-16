<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits\Pages;

use App\Http\UI\Admin\Resources\OrgUnits\OrgUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgUnits extends ListRecords
{
    protected static string $resource = OrgUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
