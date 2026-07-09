<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\ThingAttributes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThingAttributeForm
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
