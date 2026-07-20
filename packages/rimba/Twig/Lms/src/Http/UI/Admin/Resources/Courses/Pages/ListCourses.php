<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
