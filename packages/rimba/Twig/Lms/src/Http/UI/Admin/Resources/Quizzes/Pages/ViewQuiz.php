<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuiz extends ViewRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
