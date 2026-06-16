<?php

declare(strict_types=1);

namespace App\Filament\Resources\Workflows\Pages;

use App\Filament\Resources\Workflows\WorkflowResource;
use App\Trees\Branding\Actions\GetHelpAction;
use App\Trees\Branding\Concerns\HasHelpAction;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkflows extends ListRecords
{
    use HasHelpAction;

    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            GetHelpAction::make(),
            CreateAction::make(),
        ];
    }
}
