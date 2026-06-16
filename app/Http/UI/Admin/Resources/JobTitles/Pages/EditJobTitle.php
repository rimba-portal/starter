<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobTitles\Pages;

use App\Http\UI\Admin\Resources\JobTitles\JobTitleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJobTitle extends EditRecord
{
    protected static string $resource = JobTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
