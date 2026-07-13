<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\CreateAttributeOption;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\EditAttributeOption;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\ListAttributeOptions;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Schemas\AttributeOptionForm;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Tables\AttributeOptionsTable;
use Bites\Attributing\Models\AttributeOption;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttributeOptionResource extends Resource
{
    protected static ?string $model = AttributeOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Options';

    protected static ?int $navigationSort = 42;

    protected static ?string $title = 'Options';

    protected ?string $subheading = 'Attribute options for attributes with options.';

    protected static ?string $recordTitleAttribute = 'label';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return AttributeOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeOptionsTable::configure($table);
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
            'index' => ListAttributeOptions::route('/'),
            'create' => CreateAttributeOption::route('/create'),
            'edit' => EditAttributeOption::route('/{record}/edit'),
        ];
    }
}
