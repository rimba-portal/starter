<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
