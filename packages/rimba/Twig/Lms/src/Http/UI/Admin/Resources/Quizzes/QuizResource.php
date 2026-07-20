<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\CreateQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\EditQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\ListQuizzes;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\ViewQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas\QuizForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas\QuizInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Tables\QuizzesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Quiz;
use UnitEnum;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Quizzes';

    protected static ?int $navigationSort = 64;

    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
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
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'view' => ViewQuiz::route('/{record}'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}
