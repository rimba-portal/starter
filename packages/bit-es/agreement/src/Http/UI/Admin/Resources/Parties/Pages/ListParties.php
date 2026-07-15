<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParties extends ListRecords
{
    protected static string $resource = PartyResource::class;

    protected static ?string $title = 'Parties';

    protected ?string $subheading = 'Individuals or entities involved in an agreement.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
