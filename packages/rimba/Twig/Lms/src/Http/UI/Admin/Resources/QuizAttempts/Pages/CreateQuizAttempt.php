<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class CreateQuizAttempt extends CreateRecord
{
    protected static string $resource = QuizAttemptResource::class;
}
