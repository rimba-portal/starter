<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationAttribute extends CreateRecord
{
    protected static string $resource = LocationAttributeResource::class;
}
