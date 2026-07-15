<?php

declare(strict_types=1);

namespace Repo\App\Process\Filament\Resources\Workflows\Pages;

use App\Trees\Branding\Actions\GetHelpAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Repo\App\Process\Filament\Resources\Workflows\WorkflowResource;

class ViewWorkflow extends ViewRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // GetHelpAction::make(),
            EditAction::make(),
        ];
    }
}
