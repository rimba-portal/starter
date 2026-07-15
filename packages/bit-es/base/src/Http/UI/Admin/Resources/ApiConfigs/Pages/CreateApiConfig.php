<?php

declare(strict_types=1);

namespace Bites\Base\Http\UI\Admin\Resources\ApiConfigs\Pages;

use Bites\Base\Http\UI\Admin\Resources\ApiConfigs\ApiConfigResource;
use Filament\Resources\Pages\CreateRecord;

class CreateApiConfig extends CreateRecord
{
    protected static string $resource = ApiConfigResource::class;
}
