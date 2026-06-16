<?php

namespace App\Filament\Resources\Workflows\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use App\Trees\Process\Actions\CreateWorkflowFromExisting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WorkflowsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('key')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('copy')
                    ->label('Copy')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(fn($record) => app(
                        CreateWorkflowFromExisting::class
                    )->execute($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
