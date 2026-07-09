<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AgreementTypes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AgreementTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('template')
                    ->columnSpanFull(),
                Textarea::make('public_schema')
                    ->columnSpanFull(),
                Textarea::make('confidential_schema')
                    ->columnSpanFull(),
                Textarea::make('notify')
                    ->columnSpanFull(),
                TextInput::make('expiry_notify_days')
                    ->required()
                    ->numeric()
                    ->default(30),
                Toggle::make('requires_approval')
                    ->required(),
                Toggle::make('requires_signature')
                    ->required(),
                Select::make('workflow_id')
                    ->relationship('workflow', 'id'),
                Textarea::make('meta')
                    ->columnSpanFull(),
            ]);
    }
}
