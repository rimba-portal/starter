<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
