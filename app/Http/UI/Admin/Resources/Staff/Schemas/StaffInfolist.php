<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Staff\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StaffInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User')
                    ->placeholder('-'),
                TextEntry::make('org_corp_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('jobContract.id')
                    ->label('Job contract')
                    ->placeholder('-'),
                TextEntry::make('type'),
                TextEntry::make('status'),
                TextEntry::make('name')
                    ->placeholder('-'),
                TextEntry::make('staff_no')
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
