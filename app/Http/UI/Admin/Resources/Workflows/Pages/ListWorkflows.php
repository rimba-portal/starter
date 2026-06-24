<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows\Pages;

use App\Http\UI\Admin\Resources\Workflows\WorkflowResource;
use Bites\GoogleTranslate\Actions\dumpbe;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

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
