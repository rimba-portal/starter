<?php

declare(strict_types=1);

namespace App\Http\UI\Admin\Resources\RoleAssignments;

use App\Http\UI\Admin\Resources\RoleAssignments\Pages\CreateRoleAssignment;
use App\Http\UI\Admin\Resources\RoleAssignments\Pages\EditRoleAssignment;
use App\Http\UI\Admin\Resources\RoleAssignments\Pages\ListRoleAssignments;
use App\Http\UI\Admin\Resources\RoleAssignments\Pages\ViewRoleAssignment;
use App\Http\UI\Admin\Resources\RoleAssignments\Schemas\RoleAssignmentForm;
use App\Http\UI\Admin\Resources\RoleAssignments\Schemas\RoleAssignmentInfolist;
use App\Http\UI\Admin\Resources\RoleAssignments\Tables\RoleAssignmentsTable;
use App\Models\AuthZ\RoleAssignment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleAssignmentResource extends Resource
{
    protected static ?string $model = RoleAssignment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RoleAssignmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleAssignmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleAssignmentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoleAssignments::route('/'),
            'create' => CreateRoleAssignment::route('/create'),
            'view' => ViewRoleAssignment::route('/{record}'),
            'edit' => EditRoleAssignment::route('/{record}/edit'),
        ];
    }
}
