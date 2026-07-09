<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AgreementTypes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AgreementTypeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('name'),
                TextEntry::make('code'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('template')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('public_schema')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('confidential_schema')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notify')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('expiry_notify_days')
                    ->numeric(),
                IconEntry::make('requires_approval')
                    ->boolean(),
                IconEntry::make('requires_signature')
                    ->boolean(),
                TextEntry::make('workflow.id')
                    ->label('Workflow')
                    ->placeholder('-'),
                TextEntry::make('meta')
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
