<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams;

use App\Http\UI\Admin\Resources\OrgTeams\Pages\CreateOrgTeam;
use App\Http\UI\Admin\Resources\OrgTeams\Pages\EditOrgTeam;
use App\Http\UI\Admin\Resources\OrgTeams\Pages\ListOrgTeams;
use App\Http\UI\Admin\Resources\OrgTeams\Pages\ViewOrgTeam;
use App\Http\UI\Admin\Resources\OrgTeams\Schemas\OrgTeamForm;
use App\Http\UI\Admin\Resources\OrgTeams\Schemas\OrgTeamInfolist;
use App\Http\UI\Admin\Resources\OrgTeams\Tables\OrgTeamsTable;
use App\Trees\Organization\Models\OrgTeam;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class OrgTeamResource extends Resource
{
    protected static ?string $model = OrgTeam::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Organizational Items';

    protected static ?string $navigationLabel = 'Teams';

    protected static ?int $navigationSort = 13;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return OrgTeamForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return OrgTeamInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrgTeamsTable::configure($table);
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
            'index' => ListOrgTeams::route('/'),
            'create' => CreateOrgTeam::route('/create'),
            'view' => ViewOrgTeam::route('/{record}'),
            'edit' => EditOrgTeam::route('/{record}/edit'),
        ];
    }
}
