<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
