<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

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
