<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeOptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attribute_definition_id')
                    ->required()
                    ->numeric(),
                TextInput::make('value')
                    ->required(),
                TextInput::make('label'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
