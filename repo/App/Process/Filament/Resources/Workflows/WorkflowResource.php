<?php

declare(strict_types=1);

namespace Repo\App\Process\Filament\Resources\Workflows;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Repo\App\Process\Filament\Resources\Workflows\Pages\CreateWorkflow;
use Repo\App\Process\Filament\Resources\Workflows\Pages\EditWorkflow;
use Repo\App\Process\Filament\Resources\Workflows\Pages\ListWorkflows;
use Repo\App\Process\Filament\Resources\Workflows\Pages\ViewWorkflow;
use Repo\App\Process\Filament\Resources\Workflows\RelationManagers\EdgesRelationManager;
use Repo\App\Process\Filament\Resources\Workflows\RelationManagers\NodesRelationManager;
use Repo\App\Process\Filament\Resources\Workflows\Schemas\WorkflowForm;
use Repo\App\Process\Filament\Resources\Workflows\Schemas\WorkflowInfolist;
use Repo\App\Process\Filament\Resources\Workflows\Tables\WorkflowsTable;
use Repo\App\Process\Models\Workflow;

class WorkflowResource extends Resource
{
    protected static ?string $model = Workflow::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return WorkflowForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkflowInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkflowsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            NodesRelationManager::class,
            EdgesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWorkflows::route('/'),
            'create' => CreateWorkflow::route('/create'),
            'view' => ViewWorkflow::route('/{record}'),
            'edit' => EditWorkflow::route('/{record}/edit'),
        ];
    }
}
