<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows\Pages;

use App\Http\UI\Admin\Resources\Workflows\WorkflowResource;
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
