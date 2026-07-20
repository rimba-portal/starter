<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;
}
