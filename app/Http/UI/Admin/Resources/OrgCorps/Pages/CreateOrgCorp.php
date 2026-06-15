<?php

namespace App\Http\UI\Admin\Resources\OrgCorps\Pages;

use App\Http\UI\Admin\Resources\OrgCorps\OrgCorpResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgCorp extends CreateRecord
{
    protected static string $resource = OrgCorpResource::class;
}
