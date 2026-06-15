<?php

namespace App\Http\UI\Admin\Resources\RoleAssignments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RoleAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('role_id')
                    ->relationship('role', 'name')
                    ->required(),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                TextInput::make('assigned_by')
                    ->numeric(),
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
