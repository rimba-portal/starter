<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams\Pages;

use App\Http\UI\Admin\Resources\OrgTeams\OrgTeamResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrgTeam extends CreateRecord
{
    protected static string $resource = OrgTeamResource::class;
}
