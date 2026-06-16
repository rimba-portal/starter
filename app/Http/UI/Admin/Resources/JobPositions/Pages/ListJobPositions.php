<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobPositions\Pages;

use App\Http\UI\Admin\Resources\JobPositions\JobPositionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobPositions extends ListRecords
{
    protected static string $resource = JobPositionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
