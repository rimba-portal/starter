<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ApiData;

use App\Http\UI\Admin\Resources\ApiData\Pages\CreateApiData;
use App\Http\UI\Admin\Resources\ApiData\Pages\EditApiData;
use App\Http\UI\Admin\Resources\ApiData\Pages\ListApiData;
use App\Http\UI\Admin\Resources\ApiData\Schemas\ApiDataForm;
use App\Http\UI\Admin\Resources\ApiData\Tables\ApiDataTable;
use App\Models\Support\Sync\ApiData;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ApiDataResource extends Resource
{
    protected static ?string $model = ApiData::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ApiDataForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ApiDataTable::configure($table);
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
            'index' => ListApiData::route('/'),
            'create' => CreateApiData::route('/create'),
            'edit' => EditApiData::route('/{record}/edit'),
        ];
    }
}
