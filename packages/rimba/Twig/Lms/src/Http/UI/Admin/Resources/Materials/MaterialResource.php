<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\CreateMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\EditMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\ListMaterials;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\ViewMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Tables\MaterialsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Material;
use UnitEnum;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    
    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Materials';

    protected static ?int $navigationSort = 63;

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaterialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'view' => ViewMaterial::route('/{record}'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}
