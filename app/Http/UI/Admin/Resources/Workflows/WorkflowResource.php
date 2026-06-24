<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\Workflows;

use App\Http\UI\Admin\Resources\Workflows\Pages\CreateWorkflow;
use App\Http\UI\Admin\Resources\Workflows\Pages\EditWorkflow;
use App\Http\UI\Admin\Resources\Workflows\Pages\ListWorkflows;
use App\Http\UI\Admin\Resources\Workflows\Pages\ViewWorkflow;
use App\Http\UI\Admin\Resources\Workflows\RelationManagers\EdgesRelationManager;
use App\Http\UI\Admin\Resources\Workflows\RelationManagers\NodesRelationManager;
use App\Http\UI\Admin\Resources\Workflows\Schemas\WorkflowForm;
use App\Http\UI\Admin\Resources\Workflows\Schemas\WorkflowInfolist;
use App\Http\UI\Admin\Resources\Workflows\Tables\WorkflowsTable;
use App\Trees\Process\Models\Workflow;
use BackedEnum;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

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
            VersionsRelationManager::class,
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
