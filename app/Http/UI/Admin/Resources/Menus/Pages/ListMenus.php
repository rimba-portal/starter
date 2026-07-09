<?php

namespace App\Http\UI\Admin\Resources\Menus\Pages;

use App\Http\UI\Admin\Resources\Menus\MenuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Catalog';

    protected ?string $subheading = 'Manage menu catalog.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
