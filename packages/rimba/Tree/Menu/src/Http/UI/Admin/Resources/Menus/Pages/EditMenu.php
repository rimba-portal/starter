<?php

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
