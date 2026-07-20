<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('duration_minutes')
                    ->numeric(),
                TextInput::make('validity_days')
                    ->numeric(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
