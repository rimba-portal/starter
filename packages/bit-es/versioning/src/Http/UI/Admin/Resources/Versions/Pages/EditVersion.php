<?php

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditVersion extends EditRecord
{
    protected static string $resource = VersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
