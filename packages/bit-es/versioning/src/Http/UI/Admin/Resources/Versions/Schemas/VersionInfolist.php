<?php

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VersionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('versionable_type'),
                TextEntry::make('versionable_id')
                    ->numeric(),
                TextEntry::make('version'),
                TextEntry::make('major')
                    ->numeric(),
                TextEntry::make('minor')
                    ->numeric(),
                TextEntry::make('patch')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('content_type')
                    ->placeholder('-'),
                TextEntry::make('content_url')
                    ->columnSpanFull(),
                TextEntry::make('checksum')
                    ->placeholder('-'),
                TextEntry::make('effective_from')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('effective_until')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('released_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('notes')
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
