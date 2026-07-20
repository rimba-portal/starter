<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuizAttempt extends CreateRecord
{
    protected static string $resource = QuizAttemptResource::class;
}
