<?php

namespace App\Http\UI\Admin\Resources\JobPositions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class JobPositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('job_contract_id')
                    ->relationship('jobContract', 'id')
                    ->required(),
                TextInput::make('org_unit_id')
                    ->required()
                    ->numeric(),
                TextInput::make('level'),
                TextInput::make('status')
                    ->required()
                    ->default('open'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
