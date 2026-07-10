<?php

namespace App\Http\UI\Staff\Resources\Menus;

use App\Http\UI\Staff\Resources\Menus\Pages\CreateMenu;
use App\Http\UI\Staff\Resources\Menus\Pages\EditMenu;
use App\Http\UI\Staff\Resources\Menus\Pages\ListMenus;
use App\Http\UI\Staff\Resources\Menus\Schemas\MenuForm;
use App\Http\UI\Staff\Resources\Menus\Tables\MenusTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Tree\Menu\Models\Menu;
use UnitEnum;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-menu';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?int $navigationSort = 31;

    protected static ?string $recordTitleAttribute = 'title';

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
        ];
    }
}
