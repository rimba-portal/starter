<?php

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}
