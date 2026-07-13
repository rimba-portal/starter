<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttributeOptions extends ListRecords
{
    protected static string $resource = AttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
