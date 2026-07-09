<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeOptions\Pages;

use App\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeOption extends CreateRecord
{
    protected static string $resource = AttributeOptionResource::class;
}
