<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobPositions;

use App\Http\UI\Admin\Resources\JobPositions\Pages\CreateJobPosition;
use App\Http\UI\Admin\Resources\JobPositions\Pages\EditJobPosition;
use App\Http\UI\Admin\Resources\JobPositions\Pages\ListJobPositions;
use App\Http\UI\Admin\Resources\JobPositions\Pages\ViewJobPosition;
use App\Http\UI\Admin\Resources\JobPositions\Schemas\JobPositionForm;
use App\Http\UI\Admin\Resources\JobPositions\Schemas\JobPositionInfolist;
use App\Http\UI\Admin\Resources\JobPositions\Tables\JobPositionsTable;
use App\Trees\Organization\Models\JobPosition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobPositionResource extends Resource
{
    protected static ?string $model = JobPosition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JobPositionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobPositionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobPositionsTable::configure($table);
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
            'index' => ListJobPositions::route('/'),
            'create' => CreateJobPosition::route('/create'),
            'view' => ViewJobPosition::route('/{record}'),
            'edit' => EditJobPosition::route('/{record}/edit'),
        ];
    }
}
