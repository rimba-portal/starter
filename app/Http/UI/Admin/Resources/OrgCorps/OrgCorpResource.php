<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgCorps;

use App\Http\UI\Admin\Resources\OrgCorps\Pages\CreateOrgCorp;
use App\Http\UI\Admin\Resources\OrgCorps\Pages\EditOrgCorp;
use App\Http\UI\Admin\Resources\OrgCorps\Pages\ListOrgCorps;
use App\Http\UI\Admin\Resources\OrgCorps\Pages\ViewOrgCorp;
use App\Http\UI\Admin\Resources\OrgCorps\Schemas\OrgCorpForm;
use App\Http\UI\Admin\Resources\OrgCorps\Schemas\OrgCorpInfolist;
use App\Http\UI\Admin\Resources\OrgCorps\Tables\OrgCorpsTable;
use App\Models\Org\OrgCorp;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrgCorpResource extends Resource
{
    protected static ?string $model = OrgCorp::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrgCorpForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrgCorpInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgCorpsTable::configure($table);
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
            'index' => ListOrgCorps::route('/'),
            'create' => CreateOrgCorp::route('/create'),
            'view' => ViewOrgCorp::route('/{record}'),
            'edit' => EditOrgCorp::route('/{record}/edit'),
        ];
    }
}
