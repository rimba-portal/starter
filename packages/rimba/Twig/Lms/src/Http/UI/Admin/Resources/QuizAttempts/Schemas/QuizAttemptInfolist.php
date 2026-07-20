<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizAttemptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quiz.name')
                    ->label('Quiz'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('result')
                    ->placeholder('-'),
                TextEntry::make('score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('attempted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
