<?php

declare(strict_types=1);

namespace Repo\App\Process\Filament\Resources\Workflows\Pages;

use App\Trees\Branding\Actions\GetHelpAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Repo\App\Process\Filament\Resources\Workflows\WorkflowResource;

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
