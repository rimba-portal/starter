<?php

namespace App\Http\UI\Admin\Resources\JobContracts\Pages;

use App\Http\UI\Admin\Resources\JobContracts\JobContractResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJobContract extends EditRecord
{
    protected static string $resource = JobContractResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
