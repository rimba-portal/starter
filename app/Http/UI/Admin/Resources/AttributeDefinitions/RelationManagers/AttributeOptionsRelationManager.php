<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\AttributeDefinitions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttributeOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    protected static ?string $title = 'Options';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),

                Tables\Columns\TextColumn::make('label')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        TextInput::make('value')
                            ->required(),

                        TextInput::make('label')
                            ->required(),

                        Toggle::make('is_active')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('value')
                            ->required(),

                        TextInput::make('label')
                            ->required(),

                        Toggle::make('is_active'),

                        TextInput::make('sort_order')
                            ->numeric(),
                    ]),

                DeleteAction::make(),
            ]);
    }
}
