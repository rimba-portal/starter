<?php

namespace App\Http\UI\Admin\Resources\Menus;

use App\Http\UI\Admin\Resources\Menus\Pages\CreateMenu;
use App\Http\UI\Admin\Resources\Menus\Pages\EditMenu;
use App\Http\UI\Admin\Resources\Menus\Pages\ListMenus;
use App\Http\UI\Admin\Resources\Menus\Schemas\MenuForm;
use App\Http\UI\Admin\Resources\Menus\Tables\MenusTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Content Management';

    protected static ?string $navigationLabel = 'Corporate Entities';

    protected static ?int $navigationSort = 21;

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

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
            'create' => CreateMenu::route('/create'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
