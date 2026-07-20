<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditCertificate extends EditRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
