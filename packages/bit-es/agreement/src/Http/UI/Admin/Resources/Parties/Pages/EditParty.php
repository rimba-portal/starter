<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParty extends EditRecord
{
    protected static string $resource = PartyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
