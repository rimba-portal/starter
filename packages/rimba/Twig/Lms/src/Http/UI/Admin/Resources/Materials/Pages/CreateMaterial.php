<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}
