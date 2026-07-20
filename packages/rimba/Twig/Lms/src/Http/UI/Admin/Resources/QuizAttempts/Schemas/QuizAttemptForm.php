<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('quiz_id')
                    ->relationship('quiz', 'name')
                    ->required(),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                TextInput::make('result'),
                TextInput::make('score')
                    ->numeric(),
                DateTimePicker::make('attempted_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
