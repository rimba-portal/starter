<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\RoleAssignments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RoleAssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('role.name')
                    ->label('Role'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('assigned_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('orgUnit.name')
                    ->label('Org unit')
                    ->placeholder('-'),
                TextEntry::make('orgTeam.name')
                    ->label('Org team')
                    ->placeholder('-'),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
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
