<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
