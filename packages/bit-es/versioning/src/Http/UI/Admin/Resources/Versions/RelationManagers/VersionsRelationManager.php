<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Actions;

class VersionsRelationManager extends RelationManager
{
    // This matches the relationship name defined in your HasVersions trait
    protected static string $relationship = 'versions'; 

    // The field in the versions table that displays the version label (e.g., "1.0.0")
    protected static ?string $recordTitleAttribute = 'version';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('version')
                    ->required()
                    ->placeholder('e.g., 1.0.0'),
                Forms\Components\Select::make('status')
                    ->options(\Bites\Versioning\Enums\VersionStatus::class)
                    ->required(),
                Forms\Components\TextInput::make('content_url')
                    ->url()
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('version')
                    ->sortable()
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('content_type')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('effective_from')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('released_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(\Bites\Versioning\Enums\VersionStatus::class),
            ])
            ->headerActions([
                // Leverages your custom CreateVersion action under the hood
                Actions\CreateAction::make()
                    ->mutateDataUsing(function (array $data): array {
                        $data['created_by'] = auth()->id();
                        return $data;
                    }),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ]);
    }
}
