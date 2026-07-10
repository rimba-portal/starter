<?php

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShift extends CreateRecord
{
    protected static string $resource = ShiftResource::class;
}
