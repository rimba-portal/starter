<?php

namespace App\Http\UI\Admin\Resources\JobPositions\Pages;

use App\Http\UI\Admin\Resources\JobPositions\JobPositionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobPosition extends CreateRecord
{
    protected static string $resource = JobPositionResource::class;
}
