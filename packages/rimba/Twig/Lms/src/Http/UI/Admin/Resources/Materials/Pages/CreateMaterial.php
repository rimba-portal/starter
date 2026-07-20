<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}
