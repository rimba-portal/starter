<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementType extends CreateRecord
{
    protected static string $resource = AgreementTypeResource::class;
}
