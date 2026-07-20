<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('module.name')
                    ->label('Module'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('quizAttempt.id')
                    ->label('Quiz attempt')
                    ->placeholder('-'),
                TextEntry::make('evaluation.id')
                    ->label('Evaluation')
                    ->placeholder('-'),
                TextEntry::make('issued_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('issued_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expires_at')
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
