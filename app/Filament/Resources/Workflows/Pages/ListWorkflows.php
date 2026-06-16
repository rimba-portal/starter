<?php

namespace App\Filament\Resources\Workflows\Pages;

use App\Filament\Actions\HelpAction;
use App\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\VerticalAlignment;

class ListWorkflows extends ListRecords
{
    use \App\Trees\Branding\Concerns\HasHelpAction;
    
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Trees\Branding\Actions\GetHelpAction::make(),
            CreateAction::make(),
        ];
    }
}
