<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class ListQuizAttempts extends ListRecords
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
