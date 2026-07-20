<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvaluation extends ViewRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
