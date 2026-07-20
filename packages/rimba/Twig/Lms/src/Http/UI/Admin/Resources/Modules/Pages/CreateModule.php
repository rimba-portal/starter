<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;
use Filament\Resources\Pages\CreateRecord;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}
