<?php

namespace App\Filament\Resources\Workflows\Pages;

use App\Filament\Resources\Workflows\WorkflowResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Actions\HelpAction;

class EditWorkflow extends EditRecord
{
    use \App\Trees\Branding\Concerns\HasHelpAction;
    
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \App\Trees\Branding\Actions\GetHelpAction::make(),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
