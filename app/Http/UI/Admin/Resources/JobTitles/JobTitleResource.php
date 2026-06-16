<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobTitles;

use App\Http\UI\Admin\Resources\JobTitles\Pages\CreateJobTitle;
use App\Http\UI\Admin\Resources\JobTitles\Pages\EditJobTitle;
use App\Http\UI\Admin\Resources\JobTitles\Pages\ListJobTitles;
use App\Http\UI\Admin\Resources\JobTitles\Pages\ViewJobTitle;
use App\Http\UI\Admin\Resources\JobTitles\Schemas\JobTitleForm;
use App\Http\UI\Admin\Resources\JobTitles\Schemas\JobTitleInfolist;
use App\Http\UI\Admin\Resources\JobTitles\Tables\JobTitlesTable;
use App\Models\Job\JobTitle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JobTitleResource extends Resource
{
    protected static ?string $model = JobTitle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return JobTitleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JobTitleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JobTitlesTable::configure($table);
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
            'index' => ListJobTitles::route('/'),
            'create' => CreateJobTitle::route('/create'),
            'view' => ViewJobTitle::route('/{record}'),
            'edit' => EditJobTitle::route('/{record}/edit'),
        ];
    }
}
