<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows\Pages;

use App\Http\UI\Admin\Resources\Workflows\WorkflowResource;
use App\Trees\Branding\Actions\GetHelpAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

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
