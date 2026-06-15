<?php

namespace App\Http\UI\Admin\Resources\ApiData\Pages;

use App\Http\UI\Admin\Resources\ApiData\ApiDataResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListApiData extends ListRecords
{
    protected static string $resource = ApiDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
