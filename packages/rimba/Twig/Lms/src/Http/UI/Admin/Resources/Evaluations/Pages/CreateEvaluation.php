<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;

class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;
}
