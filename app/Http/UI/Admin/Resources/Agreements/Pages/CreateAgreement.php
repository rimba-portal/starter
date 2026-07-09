<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Agreements\Pages;

use App\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAgreement extends CreateRecord
{
    protected static string $resource = AgreementResource::class;
}
