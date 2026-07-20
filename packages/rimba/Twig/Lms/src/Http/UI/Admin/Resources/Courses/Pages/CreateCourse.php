<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}
