<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages\CreateModule;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages\EditModule;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages\ListModules;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages\ViewModule;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Schemas\ModuleForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Schemas\ModuleInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Tables\ModulesTable;
use Rimba\Twig\Lms\Models\Module;
use UnitEnum;

class ModuleResource extends Resource
{
    protected static ?string $model = Module::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Modules';

    protected static ?int $navigationSort = 62;

    public static function form(Schema $schema): Schema
    {
        return ModuleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ModuleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ModulesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\MaterialsRelationManager::class,
            RelationManagers\QuizRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListModules::route('/'),
            'create' => CreateModule::route('/create'),
            'view' => ViewModule::route('/{record}'),
            'edit' => EditModule::route('/{record}/edit'),
        ];
    }
}
