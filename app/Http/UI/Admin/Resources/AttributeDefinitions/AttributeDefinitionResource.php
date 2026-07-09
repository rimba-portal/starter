<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeDefinitions;

use App\Http\UI\Admin\Resources\AttributeDefinitions\Schemas\AttributeDefinitionForm;
use App\Http\UI\Admin\Resources\AttributeDefinitions\Tables\AttributeDefinitionsTable;
use BackedEnum;
use Bites\Attributing\Models\AttributeDefinition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttributeDefinitionResource extends Resource
{
    protected static ?string $model = AttributeDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Definitions';

    protected static ?int $navigationSort = 41;

    protected static ?string $title = 'Definitions';

    protected ?string $subheading = 'Attribute definitions for resource attributes.';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return AttributeDefinitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeDefinitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttributeOptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'person' => Pages\ListPersonAttributeDefinitions::route('/person'),
            'thing' => Pages\ListThingAttributeDefinitions::route('/thing'),
            'location' => Pages\ListLocationAttributeDefinitions::route('/location'),

            'index' => Pages\ListAttributeDefinitions::route('/'),
            'create' => Pages\CreateAttributeDefinition::route('/create'),
            'edit' => Pages\EditAttributeDefinition::route('/{record}/edit'),
        ];
    }
}
