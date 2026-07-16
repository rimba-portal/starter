<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

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
