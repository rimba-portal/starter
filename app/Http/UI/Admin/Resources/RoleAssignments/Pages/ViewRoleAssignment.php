<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\RoleAssignments\Pages;

use App\Http\UI\Admin\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoleAssignment extends ViewRecord
{
    protected static string $resource = RoleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
