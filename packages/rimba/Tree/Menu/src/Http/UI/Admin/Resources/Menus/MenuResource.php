<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus;

use BackedEnum;
use Bites\Versioning\Traits\ResourceHasVersionRelations;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\CreateMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\EditMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\ListMenus;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\ViewMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas\MenuForm;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas\MenuInfolist;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Tables\MenusTable;
use Rimba\Tree\Menu\Models\Menu;

class MenuResource extends Resource
{
    use ResourceHasVersionRelations;

    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MenuInfolist::configure($schema);
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
            'view' => ViewMenu::route('/{record}'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}
