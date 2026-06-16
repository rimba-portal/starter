<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits;

use App\Http\UI\Admin\Resources\OrgUnits\Pages\CreateOrgUnit;
use App\Http\UI\Admin\Resources\OrgUnits\Pages\EditOrgUnit;
use App\Http\UI\Admin\Resources\OrgUnits\Pages\ListOrgUnits;
use App\Http\UI\Admin\Resources\OrgUnits\Pages\ViewOrgUnit;
use App\Http\UI\Admin\Resources\OrgUnits\Schemas\OrgUnitForm;
use App\Http\UI\Admin\Resources\OrgUnits\Schemas\OrgUnitInfolist;
use App\Http\UI\Admin\Resources\OrgUnits\Tables\OrgUnitsTable;
use App\Models\Org\OrgUnit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrgUnitResource extends Resource
{
    protected static ?string $model = OrgUnit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrgUnitForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrgUnitInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgUnitsTable::configure($table);
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
            'index' => ListOrgUnits::route('/'),
            'create' => CreateOrgUnit::route('/create'),
            'view' => ViewOrgUnit::route('/{record}'),
            'edit' => EditOrgUnit::route('/{record}/edit'),
        ];
    }
}
