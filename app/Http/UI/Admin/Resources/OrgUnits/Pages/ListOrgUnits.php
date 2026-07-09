<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits\Pages;

use App\Http\UI\Admin\Resources\OrgUnits\OrgUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgUnits extends ListRecords
{
    protected static string $resource = OrgUnitResource::class;

    protected static ?string $title = 'Units';

    protected ?string $subheading = 'Manage organizational units within the organization.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
