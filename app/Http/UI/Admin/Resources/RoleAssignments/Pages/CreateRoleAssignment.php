<?php

namespace App\Http\UI\Admin\Resources\RoleAssignments\Pages;

use App\Http\UI\Admin\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoleAssignment extends CreateRecord
{
    protected static string $resource = RoleAssignmentResource::class;
}
