<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams\Pages;

use App\Http\UI\Admin\Resources\OrgTeams\OrgTeamResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditOrgTeam extends EditRecord
{
    protected static string $resource = OrgTeamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
