<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreement extends CreateRecord
{
    protected static string $resource = AgreementResource::class;
}
