<?php

namespace App\Http\UI\Admin\Resources\OrgCorps\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrgCorpForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('code'),
                TextInput::make('type'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
