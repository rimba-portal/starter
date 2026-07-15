<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Agreements\Pages;

use Bites\Agreement\Http\UI\Admin\Resources\Agreements\AgreementResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAgreements extends ListRecords
{
    protected static string $resource = AgreementResource::class;

    protected static ?string $title = 'Agreements';

    protected ?string $subheading = 'Binding agreement between parties for a specific purpose. Non private and confidential content of a contract agreementonly.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
