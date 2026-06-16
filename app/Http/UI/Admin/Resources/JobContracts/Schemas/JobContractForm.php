<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobContracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JobContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('job_title_id')
                    ->numeric(),
                TextInput::make('staff_id')
                    ->numeric(),
                TextInput::make('issuing_org_corp_id')
                    ->required()
                    ->numeric(),
                TextInput::make('contract_type'),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
            ]);
    }
}
