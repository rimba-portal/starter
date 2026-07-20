<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListQuizzes extends ListRecords
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
