<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EvaluationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('module.name')
                    ->label('Module')
                    ->placeholder('-'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('evaluator.name')
                    ->label('Evaluator')
                    ->placeholder('-'),
                TextEntry::make('result')
                    ->placeholder('-'),
                TextEntry::make('evaluated_at')
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
