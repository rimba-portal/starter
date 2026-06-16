<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobContracts\Pages;

use App\Http\UI\Admin\Resources\JobContracts\JobContractResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJobContract extends ViewRecord
{
    protected static string $resource = JobContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
