<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobTitles\Pages;

use App\Http\UI\Admin\Resources\JobTitles\JobTitleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobTitles extends ListRecords
{
    protected static string $resource = JobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
