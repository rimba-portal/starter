<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

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
