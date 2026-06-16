<?php

namespace App\Filament\Resources\Workflows\Pages;

use App\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Actions\HelpAction;

class ViewWorkflow extends ViewRecord
{
        use \App\Trees\Branding\Concerns\HasHelpAction;
        
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Trees\Branding\Actions\GetHelpAction::make(),
            EditAction::make(),
        ];
    }
}
