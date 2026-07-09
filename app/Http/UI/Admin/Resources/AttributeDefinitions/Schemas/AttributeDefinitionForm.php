<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeDefinitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('family')
                    ->required(),
                TextInput::make('group')
                    ->required(),
                TextInput::make('key')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('applies_to')
                    ->columnSpanFull(),
                TextInput::make('example_key'),
                TextInput::make('example_value'),
                Toggle::make('has_options')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_abac')
                    ->required(),
                Toggle::make('is_system')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
