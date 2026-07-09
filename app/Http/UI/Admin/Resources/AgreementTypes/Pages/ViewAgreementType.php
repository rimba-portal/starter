<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AgreementTypes\Pages;

use App\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAgreementType extends ViewRecord
{
    protected static string $resource = AgreementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
