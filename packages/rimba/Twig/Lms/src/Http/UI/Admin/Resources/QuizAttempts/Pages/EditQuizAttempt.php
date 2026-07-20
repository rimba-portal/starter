<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class EditQuizAttempt extends EditRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
