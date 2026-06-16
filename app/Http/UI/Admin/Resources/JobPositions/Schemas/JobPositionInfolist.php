<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobPositions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JobPositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('jobContract.id')
                    ->label('Job contract'),
                TextEntry::make('org_unit_id')
                    ->numeric(),
                TextEntry::make('level')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
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
