<?php

namespace App\Http\UI\Admin\Resources\OrgCorps\Pages;

use App\Http\UI\Admin\Resources\OrgCorps\OrgCorpResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgCorps extends ListRecords
{
    protected static string $resource = OrgCorpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
