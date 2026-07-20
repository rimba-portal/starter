<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;
}
