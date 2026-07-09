<?php

namespace App\Http\UI\Admin\Resources\LocationAttributes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LocationAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->columnSpanFull(),
                TextInput::make('attributable_type')
                    ->required(),
                TextInput::make('attributable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
