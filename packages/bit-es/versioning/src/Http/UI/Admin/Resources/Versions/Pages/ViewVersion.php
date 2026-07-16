<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewVersion extends ViewRecord
{
    protected static string $resource = VersionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
