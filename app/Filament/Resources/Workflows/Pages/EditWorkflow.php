<?php

declare(strict_types=1);

namespace App\Filament\Resources\Workflows\Pages;

use App\Filament\Resources\Workflows\WorkflowResource;
use App\Trees\Branding\Actions\GetHelpAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkflow extends EditRecord
{
    protected static string $resource = WorkflowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // GetHelpAction::make(),
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
