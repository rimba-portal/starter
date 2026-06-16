<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits\Pages;

use App\Http\UI\Admin\Resources\OrgUnits\OrgUnitResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgUnit extends CreateRecord
{
    protected static string $resource = OrgUnitResource::class;
}
