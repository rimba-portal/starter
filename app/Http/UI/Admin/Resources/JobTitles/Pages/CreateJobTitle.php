<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobTitles\Pages;

use App\Http\UI\Admin\Resources\JobTitles\JobTitleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJobTitle extends CreateRecord
{
    protected static string $resource = JobTitleResource::class;
}
