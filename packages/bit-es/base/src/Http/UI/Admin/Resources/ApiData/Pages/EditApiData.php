<?php

declare(strict_types=1);

namespace Bites\Base\Http\UI\Admin\Resources\ApiData\Pages;

use Bites\Base\Http\UI\Admin\Resources\ApiData\ApiDataResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditApiData extends EditRecord
{
    protected static string $resource = ApiDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
