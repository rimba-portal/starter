<?php

declare(strict_types=1);

namespace Repo\App\Process\Filament\Resources\Workflows\Pages;

use Bites\GoogleTranslate\Actions\dumpbe;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Repo\App\Process\Filament\Resources\Workflows\WorkflowResource;

class ListWorkflows extends ListRecords
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // dumpbe::make(),
            CreateAction::make(),
        ];
    }
}
