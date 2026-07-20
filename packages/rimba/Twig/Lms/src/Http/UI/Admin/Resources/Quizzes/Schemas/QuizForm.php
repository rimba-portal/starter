<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('pass_score')
                    ->numeric(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
