<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\LocationAttributes;

use App\Http\UI\Admin\Resources\LocationAttributes\Pages\CreateLocationAttribute;
use App\Http\UI\Admin\Resources\LocationAttributes\Pages\EditLocationAttribute;
use App\Http\UI\Admin\Resources\LocationAttributes\Pages\ListLocationAttributes;
use App\Http\UI\Admin\Resources\LocationAttributes\Pages\ViewLocationAttribute;
use App\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeForm;
use App\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeInfolist;
use App\Http\UI\Admin\Resources\LocationAttributes\Tables\LocationAttributesTable;
use BackedEnum;
use Bites\Attributing\Models\LocationAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LocationAttributeResource extends Resource
{
    protected static ?string $model = LocationAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Location Attributes';

    protected static ?int $navigationSort = 45;

    public static function form(Schema $schema): Schema
    {
        return LocationAttributeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LocationAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationAttributesTable::configure($table);
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
            'index' => ListLocationAttributes::route('/'),
            'create' => CreateLocationAttribute::route('/create'),
            'view' => ViewLocationAttribute::route('/{record}'),
            'edit' => EditLocationAttribute::route('/{record}/edit'),
        ];
    }
}
