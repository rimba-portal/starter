<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
