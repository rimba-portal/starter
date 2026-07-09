<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ApiConfigs\Pages;

use App\Http\UI\Admin\Resources\ApiConfigs\ApiConfigResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewApiConfig extends ViewRecord
{
    protected static string $resource = ApiConfigResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
