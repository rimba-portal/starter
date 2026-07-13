<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateThingAttribute extends CreateRecord
{
    protected static string $resource = ThingAttributeResource::class;
}
