<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\AgreementTypes\AgreementTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAgreementType extends EditRecord
{
    protected static string $resource = AgreementTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
