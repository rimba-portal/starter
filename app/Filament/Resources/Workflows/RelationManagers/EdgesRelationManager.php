<?php

namespace App\Filament\Resources\Workflows\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;

class EdgesRelationManager extends RelationManager
{
    protected static string $relationship = 'edges';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('from_node_id')
                    ->relationship('fromNode', 'name')
                    ->required(),

                Forms\Components\Select::make('to_node_id')
                    ->relationship('toNode', 'name')
                    ->required(),

                Forms\Components\TextInput::make('label'),

                Grid::make(3)->schema([
                    Forms\Components\TextInput::make('condition.field'),
                    Forms\Components\Select::make('condition.operator')
                        ->options([
                            '=' => '=',
                            '!=' => '!=',
                            '>' => '>',
                            '<' => '<',
                        ]),
                    Forms\Components\TextInput::make('condition.value'),
                ]),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                TextColumn::make('fromNode.name')->label('From'),
                TextColumn::make('toNode.name')->label('To'),
                TextColumn::make('label'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['condition'] = $data['condition'] ?? null;

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }
}
