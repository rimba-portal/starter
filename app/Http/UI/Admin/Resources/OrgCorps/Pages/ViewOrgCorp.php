<?php

namespace App\Http\UI\Admin\Resources\OrgCorps\Pages;

use App\Http\UI\Admin\Resources\OrgCorps\OrgCorpResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrgCorp extends ViewRecord
{
    protected static string $resource = OrgCorpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
