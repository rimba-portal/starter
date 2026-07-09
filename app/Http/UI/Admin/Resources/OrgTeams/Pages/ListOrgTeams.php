<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams\Pages;

use App\Http\UI\Admin\Resources\OrgTeams\OrgTeamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOrgTeams extends ListRecords
{
    protected static string $resource = OrgTeamResource::class;

    protected static ?string $title = 'Organizational Teams';

    protected ?string $subheading = 'Manage organizational teams and their ownership.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
