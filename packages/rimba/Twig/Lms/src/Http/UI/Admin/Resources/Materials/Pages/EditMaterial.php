<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
