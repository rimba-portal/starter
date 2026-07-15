<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\CreateAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\EditAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\ListAgreementTypes;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages\ViewAgreementType;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeForm;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Schemas\AgreementTypeInfolist;
use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Tables\AgreementTypesTable;
use Bites\Agreement\Models\AgreementType;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AgreementTypeResource extends Resource
{
    protected static ?string $model = AgreementType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Agreement Type';

    protected static ?int $navigationSort = 62;

    public static function form(Schema $schema): Schema
    {
        return AgreementTypeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgreementTypeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgreementTypesTable::configure($table);
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
            'index' => ListAgreementTypes::route('/'),
            'create' => CreateAgreementType::route('/create'),
            'view' => ViewAgreementType::route('/{record}'),
            'edit' => EditAgreementType::route('/{record}/edit'),
        ];
    }
}
