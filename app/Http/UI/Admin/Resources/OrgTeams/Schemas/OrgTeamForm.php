<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgTeams\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class OrgTeamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code'),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
