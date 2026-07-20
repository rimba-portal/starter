<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
