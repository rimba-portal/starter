<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Pages;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\VersionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateVersion extends CreateRecord
{
    protected static string $resource = VersionResource::class;
}
