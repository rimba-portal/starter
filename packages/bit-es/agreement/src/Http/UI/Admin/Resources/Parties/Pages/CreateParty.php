<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Parties\PartyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateParty extends CreateRecord
{
    protected static string $resource = PartyResource::class;
}
