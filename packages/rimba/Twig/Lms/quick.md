# PHP Files Code Dump
*Generated on: 2026-07-20 15:38:14*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Lms/src' => 'Rimba\Twig\Lms',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020338_create_biz_lms_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\database\migrations\2026_06_15_020338_create_biz_lms_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->nullable()->constrained('org_teams');
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_groups', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('course_groups');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_group_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('course_group_id')->constrained();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('modules', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('validity_days')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->nullable()->constrained();
            $table->enum('type', ['document', 'video', 'link', 'other'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('material_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quizzes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('pass_score')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quiz_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->integer('score')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('evaluator_id')->nullable()->constrained('users');
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('quiz_attempt_id')->nullable()->constrained();
            $table->foreignId('evaluation_id')->nullable()->constrained();
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->enum('status', ['valid', 'expired', 'revoked'])->default('valid');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('material_modules');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('course_groups');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('courses');
    }
};

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\CertificateResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\CertificateResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\CreateCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\EditCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\ListCertificates;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\ViewCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas\CertificateForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas\CertificateInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Tables\CertificatesTable;
use Rimba\Twig\Lms\Models\Certificate;
use UnitEnum;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Certificates';

    protected static ?int $navigationSort = 69;

    public static function form(Schema $schema): Schema
    {
        return CertificateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
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
            'index' => ListCertificates::route('/'),
            'create' => CreateCertificate::route('/create'),
            'view' => ViewCertificate::route('/{record}'),
            'edit' => EditCertificate::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Pages\CreateCertificate.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Pages\CreateCertificate.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;

class CreateCertificate extends CreateRecord
{
    protected static string $resource = CertificateResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Pages\EditCertificate.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Pages\EditCertificate.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;

class EditCertificate extends EditRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Pages\ListCertificates.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Pages\ListCertificates.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Pages\ViewCertificate.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Pages\ViewCertificate.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\CertificateResource;

class ViewCertificate extends ViewRecord
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Schemas\CertificateForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Schemas\CertificateForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name')
                    ->required(),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                Select::make('quiz_attempt_id')
                    ->relationship('quizAttempt', 'id'),
                Select::make('evaluation_id')
                    ->relationship('evaluation', 'id'),
                TextInput::make('issued_by')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
                DateTimePicker::make('issued_at'),
                DateTimePicker::make('expires_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Schemas\CertificateInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Schemas\CertificateInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('module.name')
                    ->label('Module'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('quizAttempt.id')
                    ->label('Quiz attempt')
                    ->placeholder('-'),
                TextEntry::make('evaluation.id')
                    ->label('Evaluation')
                    ->placeholder('-'),
                TextEntry::make('issued_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('issued_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expires_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Certificates\Tables\CertificatesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Certificates\Tables\CertificatesTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.name')
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->searchable(),
                TextColumn::make('quizAttempt.id')
                    ->searchable(),
                TextColumn::make('evaluation.id')
                    ->searchable(),
                TextColumn::make('issued_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('issued_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('expires_at')
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

```

---

## File: `src\Http\UI\Admin\Resources\Courses\CourseResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\CourseResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\CreateCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\EditCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\ListCourses;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages\ViewCourse;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas\CourseInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Tables\CoursesTable;
use Rimba\Twig\Lms\Models\Course;
use UnitEnum;

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
            RelationManagers\CourseModulesRelationManager::class,
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

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Pages\CreateCourse.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Pages\CreateCourse.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Pages\EditCourse.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Pages\EditCourse.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;

class EditCourse extends EditRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Pages\ListCourses.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Pages\ListCourses.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Pages\ViewCourse.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Pages\ViewCourse.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\CourseResource;

class ViewCourse extends ViewRecord
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\RelationManagers\CourseModulesRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\RelationManagers\CourseModulesRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Module;

class CourseModulesRelationManager extends RelationManager
{
    protected static string $relationship = 'courseModules';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->label('Module')
                    ->options(
                        Module::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('sequence')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sequence')
                    ->sortable(),

                TextColumn::make('module.name')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Schemas\CourseForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Schemas\CourseForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                TextInput::make('code')
                    ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Schemas\CourseInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Schemas\CourseInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CourseInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('orgTeam.name')
                    ->label('Org team')
                    ->placeholder('-'),
                TextEntry::make('code'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Courses\Tables\CoursesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Courses\Tables\CoursesTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orgTeam.name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
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

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\EvaluationResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\EvaluationResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\CreateEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\EditEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ListEvaluations;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages\ViewEvaluation;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Tables\EvaluationsTable;
use Rimba\Twig\Lms\Models\Evaluation;
use UnitEnum;

class EvaluationResource extends Resource
{
    protected static ?string $model = Evaluation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Evaluations';

    protected static ?int $navigationSort = 65;

    public static function form(Schema $schema): Schema
    {
        return EvaluationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EvaluationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EvaluationsTable::configure($table);
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
            'index' => ListEvaluations::route('/'),
            'create' => CreateEvaluation::route('/create'),
            'view' => ViewEvaluation::route('/{record}'),
            'edit' => EditEvaluation::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Pages\CreateEvaluation.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Pages\CreateEvaluation.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;

class CreateEvaluation extends CreateRecord
{
    protected static string $resource = EvaluationResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Pages\EditEvaluation.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Pages\EditEvaluation.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;

class EditEvaluation extends EditRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Pages\ListEvaluations.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Pages\ListEvaluations.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;

class ListEvaluations extends ListRecords
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Pages\ViewEvaluation.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Pages\ViewEvaluation.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\EvaluationResource;

class ViewEvaluation extends ViewRecord
{
    protected static string $resource = EvaluationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EvaluationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name'),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                Select::make('evaluator_id')
                    ->relationship('evaluator', 'name'),
                TextInput::make('result'),
                DateTimePicker::make('evaluated_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Schemas\EvaluationInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EvaluationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('module.name')
                    ->label('Module')
                    ->placeholder('-'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('evaluator.name')
                    ->label('Evaluator')
                    ->placeholder('-'),
                TextEntry::make('result')
                    ->placeholder('-'),
                TextEntry::make('evaluated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Evaluations\Tables\EvaluationsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Evaluations\Tables\EvaluationsTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Evaluations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EvaluationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.name')
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->searchable(),
                TextColumn::make('evaluator.name')
                    ->searchable(),
                TextColumn::make('result')
                    ->searchable(),
                TextColumn::make('evaluated_at')
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

```

---

## File: `src\Http\UI\Admin\Resources\Materials\MaterialResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\MaterialResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\CreateMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\EditMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\ListMaterials;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages\ViewMaterial;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas\MaterialInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Tables\MaterialsTable;
use Rimba\Twig\Lms\Models\Material;
use UnitEnum;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Materials';

    protected static ?int $navigationSort = 63;

    public static function form(Schema $schema): Schema
    {
        return MaterialForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MaterialInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MaterialsTable::configure($table);
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
            'index' => ListMaterials::route('/'),
            'create' => CreateMaterial::route('/create'),
            'view' => ViewMaterial::route('/{record}'),
            'edit' => EditMaterial::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Pages\CreateMaterial.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Pages\CreateMaterial.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Pages\EditMaterial.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Pages\EditMaterial.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Pages\ListMaterials.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Pages\ListMaterials.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

class ListMaterials extends ListRecords
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Pages\ViewMaterial.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Pages\ViewMaterial.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\MaterialResource;

class ViewMaterial extends ViewRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Schemas\MaterialForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Schemas\MaterialForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                TextInput::make('type'),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Schemas\MaterialInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Schemas\MaterialInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MaterialInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('orgTeam.name')
                    ->label('Org team')
                    ->placeholder('-'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Materials\Tables\MaterialsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Materials\Tables\MaterialsTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Materials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orgTeam.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
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

```

---

## File: `src\Http\UI\Admin\Resources\Modules\ModuleResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\ModuleResource.php`

```php
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

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Pages\CreateModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Pages\CreateModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;

class CreateModule extends CreateRecord
{
    protected static string $resource = ModuleResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Pages\EditModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Pages\EditModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;

class EditModule extends EditRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Pages\ListModules.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Pages\ListModules.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;

class ListModules extends ListRecords
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Pages\ViewModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Pages\ViewModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\ModuleResource;

class ViewModule extends ViewRecord
{
    protected static string $resource = ModuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\RelationManagers\MaterialsRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\RelationManagers\MaterialsRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Material;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materialModules';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->label('Module')
                    ->options(
                        Material::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('sequence')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sequence')
                    ->sortable(),

                TextColumn::make('material.name')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\RelationManagers\QuizRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\RelationManagers\QuizRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Quiz;

class QuizRelationManager extends RelationManager
{
    protected static string $relationship = 'quizzes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->label('Module')
                    ->options(
                        Quiz::query()
                            ->orderBy('name')
                            ->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required(),

                TextInput::make('sequence')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pass_score')
                    ->numeric()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Schemas\ModuleForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Schemas\ModuleForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ModuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('duration_minutes')
                    ->numeric(),
                TextInput::make('validity_days')
                    ->numeric(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Schemas\ModuleInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Schemas\ModuleInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ModuleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('code'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('duration_minutes')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('validity_days')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Modules\Tables\ModulesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Modules\Tables\ModulesTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Modules\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ModulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('code')
                    ->searchable(),
                TextColumn::make('duration_minutes')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('validity_days')
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

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Pages\CreateQuizAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Pages\CreateQuizAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class CreateQuizAttempt extends CreateRecord
{
    protected static string $resource = QuizAttemptResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Pages\EditQuizAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Pages\EditQuizAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class EditQuizAttempt extends EditRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Pages\ListQuizAttempts.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Pages\ListQuizAttempts.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class ListQuizAttempts extends ListRecords
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Pages\ViewQuizAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Pages\ViewQuizAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource;

class ViewQuizAttempt extends ViewRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\QuizAttemptResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\CreateQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\EditQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\ListQuizAttempts;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Pages\ViewQuizAttempt;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use Rimba\Twig\Lms\Models\QuizAttempt;
use UnitEnum;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Attempts';

    protected static ?int $navigationSort = 68;

    public static function form(Schema $schema): Schema
    {
        return QuizAttemptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizAttemptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizAttemptsTable::configure($table);
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
            'index' => ListQuizAttempts::route('/'),
            'create' => CreateQuizAttempt::route('/create'),
            'view' => ViewQuizAttempt::route('/{record}'),
            'edit' => EditQuizAttempt::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizAttemptForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('quiz_id')
                    ->relationship('quiz', 'name')
                    ->required(),
                Select::make('staff_id')
                    ->relationship('staff', 'name')
                    ->required(),
                TextInput::make('result'),
                TextInput::make('score')
                    ->numeric(),
                DateTimePicker::make('attempted_at'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Schemas\QuizAttemptInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizAttemptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quiz.name')
                    ->label('Quiz'),
                TextEntry::make('staff.name')
                    ->label('Staff'),
                TextEntry::make('result')
                    ->placeholder('-'),
                TextEntry::make('score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('attempted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\QuizAttempts\Tables\QuizAttemptsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\QuizAttempts\Tables\QuizAttemptsTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\QuizAttempts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizAttemptsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quiz.name')
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->searchable(),
                TextColumn::make('result')
                    ->searchable(),
                TextColumn::make('score')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('attempted_at')
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

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Pages\CreateQuiz.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Pages\CreateQuiz.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

class CreateQuiz extends CreateRecord
{
    protected static string $resource = QuizResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Pages\EditQuiz.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Pages\EditQuiz.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

class EditQuiz extends EditRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Pages\ListQuizzes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Pages\ListQuizzes.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

class ListQuizzes extends ListRecords
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Pages\ViewQuiz.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Pages\ViewQuiz.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\QuizResource;

class ViewQuiz extends ViewRecord
{
    protected static string $resource = QuizResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\QuizResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\QuizResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\CreateQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\EditQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\ListQuizzes;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Pages\ViewQuiz;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas\QuizForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas\QuizInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Tables\QuizzesTable;
use Rimba\Twig\Lms\Models\Quiz;
use UnitEnum;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Quizzes';

    protected static ?int $navigationSort = 64;

    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
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
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'view' => ViewQuiz::route('/{record}'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Schemas\QuizForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Schemas\QuizForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QuizForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('module_id')
                    ->relationship('module', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('pass_score')
                    ->numeric(),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Schemas\QuizInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Schemas\QuizInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('module.name')
                    ->label('Module'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('pass_score')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Quizzes\Tables\QuizzesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Http\UI\Admin\Resources\Quizzes\Tables\QuizzesTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Quizzes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QuizzesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('module.name')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('pass_score')
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

```

---

## File: `src\LmsServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\LmsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms;

use Bites\Base\Services\BitesServiceProvider;

class LmsServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Certificate.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Certificate.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Models\User;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'module_id',
    'staff_id',
    'quiz_attempt_id',
    'evaluation_id',
    'issued_by',
    'status',
    'issued_at',
    'expires_at',
    'attributes',
])]
class Certificate extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'module_id' => 'integer',
            'staff_id' => 'integer',
            'quiz_attempt_id' => 'integer',
            'evaluation_id' => 'integer',
            'issued_by' => 'integer',
            'issued_at' => 'timestamp',
            'expires_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

```

---

## File: `src\Models\Course.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Course.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'code',
    'title',
    'description',
    'is_active',
    'attributes',
])]
class Course extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'org_team_id' => 'integer',
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function courseModules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function courseGroupAssignments(): HasMany
    {
        return $this->hasMany(CourseGroupAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\CourseGroup.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseGroup.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id',
    'name',
    'description',
    'attributes',
])]
class CourseGroup extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'parent_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(CourseGroup::class);
    }

    public function courseGroupAssignments(): HasMany
    {
        return $this->hasMany(CourseGroupAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseGroup::class);
    }
}

```

---

## File: `src\Models\CourseGroupAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseGroupAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id',
    'course_group_id',
    'attributes',
])]
class CourseGroupAssignment extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'course_id' => 'integer',
            'course_group_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function courseGroup(): BelongsTo
    {
        return $this->belongsTo(CourseGroup::class);
    }
}

```

---

## File: `src\Models\CourseModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id',
    'module_id',
    'sequence',
    'attributes',
])]
class CourseModule extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'course_id' => 'integer',
            'module_id' => 'integer',
            'sequence' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public static function seedMappings(): array
    {
        return [
            'course_code' => fn (string $value): array => [
                'course_id' => Course::query()
                    ->where('code', $value)
                    ->firstOrFail()
                    ->id,
            ],

            'module_code' => fn (string $value): array => [
                'module_id' => Module::query()
                    ->where('code', $value)
                    ->firstOrFail()
                    ->id,
            ],
        ];
    }
}

```

---

## File: `src\Models\Evaluation.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Evaluation.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Models\User;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_id',
    'staff_id',
    'evaluator_id',
    'result',
    'evaluated_at',
    'attributes',
])]
class Evaluation extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'module_id' => 'integer',
            'staff_id' => 'integer',
            'evaluator_id' => 'integer',
            'evaluated_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

```

---

## File: `src\Models\Material.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Material.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\OrgTeam;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'type',
    'name',
    'description',
    'attributes',
])]
class Material extends Model
{
    use HasFactory;
    use HasVersions;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'org_team_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function materialModules(): HasMany
    {
        return $this->hasMany(MaterialModule::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\MaterialModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\MaterialModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'material_id',
    'module_id',
    'sequence',
    'attributes',
])]
class MaterialModule extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'material_id' => 'integer',
            'module_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}

```

---

## File: `src\Models\Module.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Module.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'duration_minutes',
    'validity_days',
    'attributes',
])]
class Module extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function courseModules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function materialModules(): HasMany
    {
        return $this->hasMany(MaterialModule::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}

```

---

## File: `src\Models\Quiz.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Quiz.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_id',
    'name',
    'description',
    'pass_score',
    'attributes',
])]
class Quiz extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'module_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public static function seedMappings(): array
    {
        return [
            'module_code' => fn (string $value): array => [
                'module_id' => Module::query()
                    ->where('code', $value)
                    ->firstOrFail()
                    ->id,
            ],
        ];
    }
}

```

---

## File: `src\Models\QuizAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\QuizAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'quiz_id',
    'staff_id',
    'result',
    'score',
    'attempted_at',
    'attributes',
])]
class QuizAttempt extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'quiz_id' => 'integer',
            'staff_id' => 'integer',
            'attempted_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

```

---

