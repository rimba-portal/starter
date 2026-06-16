<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobContracts\Pages;

use App\Http\UI\Admin\Resources\JobContracts\JobContractResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobContracts extends ListRecords
{
    protected static string $resource = JobContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
