<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\CreateEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\EditEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ListEvaluations;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ViewEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Tables\EvaluationsTable;
use Rimba\Twig\Lms\Models\Evaluation;
use UnitEnum;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Evaluations';

    protected static ?int $navigationSort = 65;

    public static function form(Schema $schema): Schema
    {
        return EvaluationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
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
            'index' => ListEvaluations::route('/'),
            'create' => CreateEvaluation::route('/create'),
            'view' => ViewEvaluation::route('/{record}'),
            'edit' => EditEvaluation::route('/{record}/edit'),
        ];
    }
}
