<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\OrgUnits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrgUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_corp_id')
                    ->relationship('orgCorp', 'name'),
                Select::make('parent_id')
                    ->relationship('parent', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('uuid')
                    ->label('UUID')
                    ->required(),
                TextInput::make('code'),
                TextInput::make('description'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}
