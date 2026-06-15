<?php

namespace App\Http\UI\Admin\Resources\JobContracts;

use App\Http\UI\Admin\Resources\JobContracts\Pages\CreateJobContract;
use App\Http\UI\Admin\Resources\JobContracts\Pages\EditJobContract;
use App\Http\UI\Admin\Resources\JobContracts\Pages\ListJobContracts;
use App\Http\UI\Admin\Resources\JobContracts\Pages\ViewJobContract;
use App\Http\UI\Admin\Resources\JobContracts\Schemas\JobContractForm;
use App\Http\UI\Admin\Resources\JobContracts\Schemas\JobContractInfolist;
use App\Http\UI\Admin\Resources\JobContracts\Tables\JobContractsTable;
use App\Models\Job\JobContract;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobContractResource extends Resource
{
    protected static ?string $model = JobContract::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JobContractForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobContractInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobContractsTable::configure($table);
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
            'index' => ListJobContracts::route('/'),
            'create' => CreateJobContract::route('/create'),
            'view' => ViewJobContract::route('/{record}'),
            'edit' => EditJobContract::route('/{record}/edit'),
        ];
    }
}
