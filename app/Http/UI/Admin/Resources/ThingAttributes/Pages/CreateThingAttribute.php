<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ThingAttributes\Pages;

use App\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateThingAttribute extends CreateRecord
{
    protected static string $resource = ThingAttributeResource::class;
}
