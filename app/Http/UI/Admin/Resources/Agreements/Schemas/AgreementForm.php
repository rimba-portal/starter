<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Agreements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('agreement_type')
                    ->required(),
                TextInput::make('contract_no'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('summary')
                    ->columnSpanFull(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                DatePicker::make('renewal_date'),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                Textarea::make('terms')
                    ->columnSpanFull(),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}
