<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                TextInput::make('type'),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
