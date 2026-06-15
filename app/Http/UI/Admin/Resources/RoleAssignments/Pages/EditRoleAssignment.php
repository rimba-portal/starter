<?php

namespace App\Http\UI\Admin\Resources\RoleAssignments\Pages;

use App\Http\UI\Admin\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRoleAssignment extends EditRecord
{
    protected static string $resource = RoleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
