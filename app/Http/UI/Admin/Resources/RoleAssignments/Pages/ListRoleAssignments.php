<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\RoleAssignments\Pages;

use App\Http\UI\Admin\Resources\RoleAssignments\RoleAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoleAssignments extends ListRecords
{
    protected static string $resource = RoleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
