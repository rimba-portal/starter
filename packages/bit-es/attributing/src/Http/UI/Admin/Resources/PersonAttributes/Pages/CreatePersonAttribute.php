<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAttribute extends CreateRecord
{
    protected static string $resource = PersonAttributeResource::class;
}
