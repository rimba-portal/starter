<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Agreements\Pages;

use App\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAgreement extends EditRecord
{
    protected static string $resource = AgreementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
