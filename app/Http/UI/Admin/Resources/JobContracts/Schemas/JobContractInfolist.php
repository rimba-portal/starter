<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\JobContracts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class JobContractInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('uuid')
                    ->label('UUID'),
                TextEntry::make('job_title_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('staff_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('issuing_org_corp_id')
                    ->numeric(),
                TextEntry::make('contract_type')
                    ->placeholder('-'),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
