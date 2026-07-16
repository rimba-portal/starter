<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}
