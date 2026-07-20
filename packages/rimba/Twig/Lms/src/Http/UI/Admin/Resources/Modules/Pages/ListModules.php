<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
