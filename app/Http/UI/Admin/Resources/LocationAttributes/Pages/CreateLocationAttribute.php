<?php

namespace App\Http\UI\Admin\Resources\LocationAttributes\Pages;

use App\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationAttribute extends CreateRecord
{
    protected static string $resource = LocationAttributeResource::class;
}
