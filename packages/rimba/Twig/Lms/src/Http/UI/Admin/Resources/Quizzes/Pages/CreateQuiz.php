<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
}
