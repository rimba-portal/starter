<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ApiData\Pages;

use App\Http\UI\Admin\Resources\ApiData\ApiDataResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApiData extends CreateRecord
{
    protected static string $resource = ApiDataResource::class;
}
