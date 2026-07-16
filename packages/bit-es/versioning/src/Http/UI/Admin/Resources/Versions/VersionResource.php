<?php

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages\CreateVersion;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages\EditVersion;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages\ListVersions;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages\ViewVersion;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionForm;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Schemas\VersionInfolist;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\Tables\VersionsTable;
use BackedEnum;
use Bites\Versioning\Models\Version;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VersionResource extends Resource
{
    protected static ?string $model = Version::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VersionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return VersionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VersionsTable::configure($table);
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
            'index' => ListVersions::route('/'),
            'create' => CreateVersion::route('/create'),
            'view' => ViewVersion::route('/{record}'),
            'edit' => EditVersion::route('/{record}/edit'),
        ];
    }
}
