<?php

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewMenu extends ViewRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
