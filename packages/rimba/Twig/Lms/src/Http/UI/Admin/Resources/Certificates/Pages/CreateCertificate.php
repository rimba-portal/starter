<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;
}
