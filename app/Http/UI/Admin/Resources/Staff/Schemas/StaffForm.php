<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Staff\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('org_corp_id')
                    ->numeric(),
                Select::make('job_contract_id')
                    ->relationship('jobContract', 'id'),
                TextInput::make('type')
                    ->required()
                    ->default('FTE'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                TextInput::make('name'),
                TextInput::make('staff_no'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
