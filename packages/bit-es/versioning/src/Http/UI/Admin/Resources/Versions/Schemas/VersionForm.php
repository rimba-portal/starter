<?php

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VersionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('versionable_type')
                    ->required(),
                TextInput::make('versionable_id')
                    ->required()
                    ->numeric(),
                TextInput::make('version')
                    ->required(),
                TextInput::make('major')
                    ->required()
                    ->numeric(),
                TextInput::make('minor')
                    ->required()
                    ->numeric(),
                TextInput::make('patch')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('content_type'),
                Textarea::make('content_url')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('checksum'),
                DateTimePicker::make('effective_from'),
                DateTimePicker::make('effective_until'),
                DateTimePicker::make('released_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
