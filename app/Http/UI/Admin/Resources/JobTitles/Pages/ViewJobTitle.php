<?php

namespace App\Http\UI\Admin\Resources\JobTitles\Pages;

use App\Http\UI\Admin\Resources\JobTitles\JobTitleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobTitle extends ViewRecord
{
    protected static string $resource = JobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
