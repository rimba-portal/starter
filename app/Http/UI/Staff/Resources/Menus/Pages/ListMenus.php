<?php

namespace App\Http\UI\Staff\Resources\Menus\Pages;

use App\Http\UI\Staff\Resources\Menus\MenuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Menu';

    protected ?string $subheading = 'Catalog of all company links.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
