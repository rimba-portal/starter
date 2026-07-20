<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvaluation extends EditRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
