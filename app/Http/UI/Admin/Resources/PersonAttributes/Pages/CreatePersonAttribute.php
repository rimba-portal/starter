<?php

namespace App\Http\UI\Admin\Resources\PersonAttributes\Pages;

use App\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAttribute extends CreateRecord
{
    protected static string $resource = PersonAttributeResource::class;
}
