<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewCertificate extends ViewRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
