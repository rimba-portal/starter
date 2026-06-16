<?php

declare(strict_types=1);

namespace App\Filament\Resources\Workflows\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NodesRelationManager extends RelationManager
{
    protected static string $relationship = 'nodes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->options([
                        'start' => 'Start',
                        'process' => 'Process',
                        'decision' => 'Decision',
                        'end' => 'End',
                    ])
                    ->required(),

                Select::make('assignment.type')
                    ->label('Assignment Type')
                    ->options([
                        'job_position' => 'Job Position',
                        'staff' => 'Staff',
                        'dynamic' => 'Dynamic',
                    ]),

                TextInput::make('assignment.value')
                    ->label('Assignment Value'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
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
        $data['config'] = [
            'assignment' => $data['assignment'] ?? null,
        ];

        unset($data['assignment']);

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mutateFormDataBeforeSave($data);
    }
}
