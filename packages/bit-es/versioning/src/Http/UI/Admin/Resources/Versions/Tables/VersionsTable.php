<?php

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VersionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('versionable_type')
                    ->searchable(),
                TextColumn::make('versionable_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('version')
                    ->searchable(),
                TextColumn::make('major')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('minor')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('patch')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('content_type')
                    ->searchable(),
                TextColumn::make('checksum')
                    ->searchable(),
                TextColumn::make('effective_from')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('effective_until')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('released_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
