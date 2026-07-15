<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties;

use BackedEnum;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\CreateParty;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\EditParty;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages\ListParties;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Schemas\PartyForm;
use Bites\Agreement\Http\UI\Admin\Resources\Parties\Tables\PartiesTable;
use Bites\Agreement\Models\Party;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PartyResource extends Resource
{
    protected static ?string $model = Party::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'role';

    protected static string|UnitEnum|null $navigationGroup = 'Agreements';

    protected static ?string $navigationLabel = 'Party';

    protected static ?int $navigationSort = 63;

    public static function form(Schema $schema): Schema
    {
        return PartyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartiesTable::configure($table);
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
            'index' => ListParties::route('/'),
            'create' => CreateParty::route('/create'),
            'edit' => EditParty::route('/{record}/edit'),
        ];
    }
}
