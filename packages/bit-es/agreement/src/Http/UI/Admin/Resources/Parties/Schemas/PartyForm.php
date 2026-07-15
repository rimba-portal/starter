<?php

declare(strict_types=1);

namespace Bites\Agreement\Http\UI\Admin\Resources\Parties\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('agreement_id')
                    ->relationship('agreement', 'title')
                    ->required(),
                TextInput::make('role'),
                Toggle::make('is_signatory')
                    ->required(),
                Toggle::make('notify_on_expiry')
                    ->required(),
                Textarea::make('meta')
                    ->columnSpanFull(),
                TextInput::make('party_type')
                    ->required(),
                TextInput::make('party_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
