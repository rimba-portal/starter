<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\CreateCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\EditCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\ListCourses;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\ViewCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Tables\CoursesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Course;
use UnitEnum;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\RelationManagers\CourseModulesRelationManager;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Courses';

    protected static ?int $navigationSort = 61;

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourseInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CourseModulesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'view' => ViewCourse::route('/{record}'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
}
