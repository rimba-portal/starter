<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\CreatePersonAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\EditPersonAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\ListPersonAttributes;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Schemas\PersonAttributeForm;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Tables\PersonAttributesTable;
use Bites\Attributing\Models\PersonAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PersonAttributeResource extends Resource
{
    protected static ?string $model = PersonAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Person Attributes';

    protected static ?int $navigationSort = 43;

    public static function form(Schema $schema): Schema
    {
        return PersonAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonAttributesTable::configure($table);
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
            'index' => ListPersonAttributes::route('/'),
            'create' => CreatePersonAttribute::route('/create'),
            'edit' => EditPersonAttribute::route('/{record}/edit'),
        ];
    }
}
