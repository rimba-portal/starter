<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
}
