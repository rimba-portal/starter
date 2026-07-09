<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams\Pages;

use App\Http\UI\Admin\Resources\OrgTeams\OrgTeamResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewOrgTeam extends ViewRecord
{
    protected static string $resource = OrgTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
