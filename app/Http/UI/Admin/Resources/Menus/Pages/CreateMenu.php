<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Menus\Pages;

use App\Http\UI\Admin\Resources\Menus\MenuResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}
