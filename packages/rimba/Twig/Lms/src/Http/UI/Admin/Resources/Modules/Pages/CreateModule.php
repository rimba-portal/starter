<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}
