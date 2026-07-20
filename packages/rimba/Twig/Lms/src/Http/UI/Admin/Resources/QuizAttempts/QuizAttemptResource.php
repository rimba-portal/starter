<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\CreateQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\EditQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\ListQuizAttempts;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\ViewQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\QuizAttempt;
use UnitEnum;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Attempts';

    protected static ?int $navigationSort = 68;

    public static function form(Schema $schema): Schema
    {
        return QuizAttemptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizAttemptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizAttemptsTable::configure($table);
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
            'index' => ListQuizAttempts::route('/'),
            'create' => CreateQuizAttempt::route('/create'),
            'view' => ViewQuizAttempt::route('/{record}'),
            'edit' => EditQuizAttempt::route('/{record}/edit'),
        ];
    }
}
