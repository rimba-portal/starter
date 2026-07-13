<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\CreateThingAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\EditThingAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\ListThingAttributes;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Schemas\ThingAttributeForm;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Tables\ThingAttributesTable;
use Bites\Attributing\Models\ThingAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ThingAttributeResource extends Resource
{
    protected static ?string $model = ThingAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Thing Attributes';

    protected static ?int $navigationSort = 44;

    public static function form(Schema $schema): Schema
    {
        return ThingAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThingAttributesTable::configure($table);
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
            'index' => ListThingAttributes::route('/'),
            'create' => CreateThingAttribute::route('/create'),
            'edit' => EditThingAttribute::route('/{record}/edit'),
        ];
    }
}
