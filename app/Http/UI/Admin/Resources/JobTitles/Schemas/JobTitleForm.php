<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobTitles\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class JobTitleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('jobgrade'),
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
                TextInput::make('masco_code'),
            ]);
    }
}
