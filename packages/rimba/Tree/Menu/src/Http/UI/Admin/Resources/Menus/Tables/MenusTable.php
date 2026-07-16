<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('currentVersion')
                    ->label('Current Version')
                    ->getStateUsing(function ($record) {
                        // This safely checks your trait method and extracts the column you want
                        return $record->currentVersion()?->version_number ?? 'No Version';
                    }),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('icon')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('permission')
                    ->searchable(),
                TextColumn::make('panel')
                    ->searchable(),
                IconColumn::make('is_visible')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort')
                    ->numeric()
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
