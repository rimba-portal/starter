<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\CreateAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\EditAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\ListAgreements;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages\ViewAgreement;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas\AgreementForm;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Schemas\AgreementInfolist;
use Bites\Agreement\Http\UI\Admin\Resources\Agreements\Tables\AgreementsTable;
use Bites\Agreement\Models\Agreement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AgreementResource extends Resource
{
    protected static ?string $model = Agreement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Agreement';

    protected static ?int $navigationSort = 61;

    public static function form(Schema $schema): Schema
    {
        return AgreementForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AgreementInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgreementsTable::configure($table);
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
            'index' => ListAgreements::route('/'),
            'create' => CreateAgreement::route('/create'),
            'view' => ViewAgreement::route('/{record}'),
            'edit' => EditAgreement::route('/{record}/edit'),
        ];
    }
}
