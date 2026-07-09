<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AgreementTypes\Pages;

use App\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreementType extends CreateRecord
{
    protected static string $resource = AgreementTypeResource::class;
}
