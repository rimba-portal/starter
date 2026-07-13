<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ApiConfigs;

use App\Http\UI\Admin\Resources\ApiConfigs\Pages\CreateApiConfig;
use App\Http\UI\Admin\Resources\ApiConfigs\Pages\EditApiConfig;
use App\Http\UI\Admin\Resources\ApiConfigs\Pages\ListApiConfigs;
use App\Http\UI\Admin\Resources\ApiConfigs\Pages\ViewApiConfig;
use App\Http\UI\Admin\Resources\ApiConfigs\Schemas\ApiConfigForm;
use App\Http\UI\Admin\Resources\ApiConfigs\Schemas\ApiConfigInfolist;
use App\Http\UI\Admin\Resources\ApiConfigs\Tables\ApiConfigsTable;
use BackedEnum;
use Bites\Base\Models\ApiConfig;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ApiConfigResource extends Resource
{
    protected static ?string $model = ApiConfig::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Data Synchronization';

    protected static ?string $navigationLabel = 'Configurations';

    protected static ?int $navigationSort = 41;

    public static function form(Schema $schema): Schema
    {
        return ApiConfigForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ApiConfigInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiConfigsTable::configure($table);
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
            'index' => ListApiConfigs::route('/'),
            'create' => CreateApiConfig::route('/create'),
            'view' => ViewApiConfig::route('/{record}'),
            'edit' => EditApiConfig::route('/{record}/edit'),
        ];
    }
}
