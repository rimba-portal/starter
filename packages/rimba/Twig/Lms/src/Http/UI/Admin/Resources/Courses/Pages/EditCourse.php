<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
