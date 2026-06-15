<?php

namespace App\Http\UI\Admin\Resources\OrgCorps\Pages;

use App\Http\UI\Admin\Resources\OrgCorps\OrgCorpResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrgCorp extends EditRecord
{
    protected static string $resource = OrgCorpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
