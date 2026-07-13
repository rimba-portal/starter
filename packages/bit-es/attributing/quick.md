# PHP Files Code Dump
*Generated on: 2026-07-13 16:26:32*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\attributing`*

---

## File: `config\attributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\config\attributes.php`

```php
<?php

declare(strict_types=1);

return [

    'navigation_group' => 'Settings',

    'navigation_sort' => 90,

    'families' => [
        'person' => 'Person',
        'thing' => 'Things',
        'location' => 'Locations',
    ],

    'groups' => [
        'person' => [
            'identity' => 'Identity',
            'organization' => 'Organization',
            'skills' => 'Skills',
            'qualification' => 'Qualification',
            'security' => 'Security',
            'requirements' => 'Requirements',
            'health' => 'Health',
        ],

        'thing' => [
            'identification' => 'Identification',
            'classification' => 'Classification',
            'technical' => 'Technical',
            'lifecycle' => 'Lifecycle',
            'maintenance' => 'Maintenance',
            'document' => 'Document',
        ],

        'location' => [
            'geography' => 'Geography',
            'enterprise' => 'Enterprise',
            'facility' => 'Facility',
            'operations' => 'Operations',
            'security' => 'Security',
            'environment' => 'Environment',
            'emergency' => 'Emergency',
        ],
    ],

];

```

---

## File: `database\migrations\0002_01_01_000101_create_attributing_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\database\migrations\0002_01_01_000101_create_attributing_tables.php`

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

        Schema::create('attribute_definitions', function (Blueprint $table): void {
            $table->id();
            $table->string('family'); // personnel, asset, area
            $table->string('group');  // identity, skills, security, etc.
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('applies_to')->nullable();
            $table->string('example_key')->nullable();
            $table->string('example_value')->nullable();
            $table->boolean('has_options')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_abac')->default(false);
            $table->boolean('is_system')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['family', 'group']);
            $table->index(['family', 'key']);
        });

        Schema::create('attribute_options', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('attribute_definition_id')->constrained('attribute_definitions')->cascadeOnDelete();
            $table->string('value');
            $table->string('label')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['attribute_definition_id', 'value']);
        });

        // for consumption of users, staffs (actual), job_posts (defined)
        Schema::create('person_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'gender', 'dob', 'phone'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });
        // for consumption of assets, equipment, (actual,defined)
        Schema::create('location_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'dimensions', 'type', 'location'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });
        // for consumption of assets, equipment, (actual,defined)
        Schema::create('thing_attributes', function (Blueprint $table): void {
            $table->id();
            $table->string('key'); // e.g. 'dimensions', 'type', 'location'
            $table->text('value')->nullable();
            $table->morphs('attributable'); // adds adds attributable_id and attributable_type
            $table->timestamps();

            $table->index('key');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thing_attributes');
        Schema::dropIfExists('location_attributes');
        Schema::dropIfExists('person_attributes');
        Schema::dropIfExists('attribute_options');
        Schema::dropIfExists('attribute_definitions');
    }
};

```

---

## File: `src\AttributingServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\AttributingServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing;

use App\Services\BitesServiceProvider;
use Bites\Attributing\Macros\LockWhenFilledMacro;

class AttributingServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/attributes.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        LockWhenFilledMacro::register();
        // dd(config('bites.groups.person'));
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Schemas\AttributeDefinitionForm;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Tables\AttributeDefinitionsTable;
use Bites\Attributing\Models\AttributeDefinition;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttributeDefinitionResource extends Resource
{
    protected static ?string $model = AttributeDefinition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Definitions';

    protected static ?int $navigationSort = 41;

    protected static ?string $title = 'Definitions';

    protected ?string $subheading = 'Attribute definitions for resource attributes.';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return AttributeDefinitionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeDefinitionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AttributeOptionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'person' => Pages\ListPersonAttributeDefinitions::route('/person'),
            'thing' => Pages\ListThingAttributeDefinitions::route('/thing'),
            'location' => Pages\ListLocationAttributeDefinitions::route('/location'),

            'index' => Pages\ListAttributeDefinitions::route('/'),
            'create' => Pages\CreateAttributeDefinition::route('/create'),
            'edit' => Pages\EditAttributeDefinition::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\CreateAttributeDefinition.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\CreateAttributeDefinition.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeDefinition extends CreateRecord
{
    protected static string $resource = AttributeDefinitionResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\EditAttributeDefinition.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\EditAttributeDefinition.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeDefinition extends EditRecord
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListAttributeDefinitions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListAttributeDefinitions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'person' => Tab::make('Person')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('family', 'person')),
            'thing' => Tab::make('Thing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('family', 'thing')),
            'location' => Tab::make('Location')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('family', 'location')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListLocationAttributeDefinitions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListLocationAttributeDefinitions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLocationAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected string $family = 'location';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('family', $this->family)
                ),
            ...$this->getGroupTabs(),
        ];
    }

    protected function getGroupTabs(): array
    {
        $tabs = [];

        foreach (config('bites.groups.'.$this->family, []) as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('family', $this->family)
                        ->where('group', $key)
                );
        }

        return $tabs;
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListPersonAttributeDefinitions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListPersonAttributeDefinitions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListPersonAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected string $family = 'person';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('family', $this->family)
                ),
            ...$this->getGroupTabs(),
        ];
    }

    protected function getGroupTabs(): array
    {
        $tabs = [];

        foreach (config('bites.groups.'.$this->family, []) as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('family', $this->family)
                        ->where('group', $key)
                );
        }

        return $tabs;
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListThingAttributeDefinitions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Pages\ListThingAttributeDefinitions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\AttributeDefinitionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListThingAttributeDefinitions extends ListRecords
{
    protected static string $resource = AttributeDefinitionResource::class;

    protected string $family = 'thing';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('family', $this->family)
                ),
            ...$this->getGroupTabs(),
        ];
    }

    protected function getGroupTabs(): array
    {
        $tabs = [];

        foreach (config('bites.groups.'.$this->family, []) as $key => $label) {
            $tabs[$key] = Tab::make($label)
                ->modifyQueryUsing(
                    fn (Builder $query) => $query
                        ->where('family', $this->family)
                        ->where('group', $key)
                );
        }

        return $tabs;
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\RelationManagers\AttributeOptionsRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\RelationManagers\AttributeOptionsRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class AttributeOptionsRelationManager extends RelationManager
{
    protected static string $relationship = 'options';

    protected static ?string $title = 'Options';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('value')
                    ->searchable(),

                Tables\Columns\TextColumn::make('label')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->schema([
                        TextInput::make('value')
                            ->required(),

                        TextInput::make('label')
                            ->required(),

                        Toggle::make('is_active')
                            ->default(true),

                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        TextInput::make('value')
                            ->required(),

                        TextInput::make('label')
                            ->required(),

                        Toggle::make('is_active'),

                        TextInput::make('sort_order')
                            ->numeric(),
                    ]),

                DeleteAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Schemas\AttributeDefinitionForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Schemas\AttributeDefinitionForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeDefinitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('family')
                    ->required(),
                TextInput::make('group')
                    ->required(),
                TextInput::make('key')
                    ->required(),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('applies_to')
                    ->columnSpanFull(),
                TextInput::make('example_key'),
                TextInput::make('example_value'),
                Toggle::make('has_options')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_abac')
                    ->required(),
                Toggle::make('is_system')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeDefinitions\Tables\AttributeDefinitionsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeDefinitions\Tables\AttributeDefinitionsTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeDefinitions\Tables;

use Bites\Attributing\Models\AttributeDefinition;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class AttributeDefinitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('example_key')
                    ->searchable(),
                TextColumn::make('example_value')
                    ->searchable(),
                IconColumn::make('has_options')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_abac')
                    ->boolean(),
                IconColumn::make('is_system')
                    ->boolean(),
                TextColumn::make('sort_order')
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
            ->groups([
                Group::make('segment')
                    ->getTitleFromRecordUsing(fn (AttributeDefinition $record): string => sprintf('%s - %s', $record->family, $record->group)),
            ])->defaultGroup('Segment')
            ->filters([
                //
            ])
            ->recordActions([
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

## File: `src\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\CreateAttributeOption;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\EditAttributeOption;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages\ListAttributeOptions;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Schemas\AttributeOptionForm;
use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Tables\AttributeOptionsTable;
use Bites\Attributing\Models\AttributeOption;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AttributeOptionResource extends Resource
{
    protected static ?string $model = AttributeOption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Options';

    protected static ?int $navigationSort = 42;

    protected static ?string $title = 'Options';

    protected ?string $subheading = 'Attribute options for attributes with options.';

    protected static ?string $recordTitleAttribute = 'label';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return AttributeOptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeOptionsTable::configure($table);
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
            'index' => ListAttributeOptions::route('/'),
            'create' => CreateAttributeOption::route('/create'),
            'edit' => EditAttributeOption::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeOptions\Pages\CreateAttributeOption.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\Pages\CreateAttributeOption.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeOption extends CreateRecord
{
    protected static string $resource = AttributeOptionResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeOptions\Pages\EditAttributeOption.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\Pages\EditAttributeOption.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAttributeOption extends EditRecord
{
    protected static string $resource = AttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeOptions\Pages\ListAttributeOptions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\Pages\ListAttributeOptions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\AttributeOptionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttributeOptions extends ListRecords
{
    protected static string $resource = AttributeOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeOptions\Schemas\AttributeOptionForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\Schemas\AttributeOptionForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AttributeOptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('attribute_definition_id')
                    ->required()
                    ->numeric(),
                TextInput::make('value')
                    ->required(),
                TextInput::make('label'),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\AttributeOptions\Tables\AttributeOptionsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\AttributeOptions\Tables\AttributeOptionsTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\AttributeOptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttributeOptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute_definition_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('value')
                    ->searchable(),
                TextColumn::make('label')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort_order')
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

## File: `src\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages\CreateLocationAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages\EditLocationAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages\ListLocationAttributes;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages\ViewLocationAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeForm;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeInfolist;
use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Tables\LocationAttributesTable;
use Bites\Attributing\Models\LocationAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class LocationAttributeResource extends Resource
{
    protected static ?string $model = LocationAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Location Attributes';

    protected static ?int $navigationSort = 45;

    public static function form(Schema $schema): Schema
    {
        return LocationAttributeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return LocationAttributeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationAttributesTable::configure($table);
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
            'index' => ListLocationAttributes::route('/'),
            'create' => CreateLocationAttribute::route('/create'),
            'view' => ViewLocationAttribute::route('/{record}'),
            'edit' => EditLocationAttribute::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Pages\CreateLocationAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Pages\CreateLocationAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLocationAttribute extends CreateRecord
{
    protected static string $resource = LocationAttributeResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Pages\EditLocationAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Pages\EditLocationAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLocationAttribute extends EditRecord
{
    protected static string $resource = LocationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),

            Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.location')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Pages\ListLocationAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Pages\ListLocationAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLocationAttributes extends ListRecords
{
    protected static string $resource = LocationAttributeResource::class;

    protected static ?string $title = 'Location Attributes';

    protected ?string $subheading = 'Attribute for location resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.location')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Pages\ViewLocationAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Pages\ViewLocationAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\LocationAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLocationAttribute extends ViewRecord
{
    protected static string $resource = LocationAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Location Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.location')),

        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LocationAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->columnSpanFull(),
                TextInput::make('attributable_type')
                    ->required(),
                TextInput::make('attributable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Schemas\LocationAttributeInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LocationAttributeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('key'),
                TextEntry::make('value')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('attributable_type'),
                TextEntry::make('attributable_id')
                    ->numeric(),
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

## File: `src\Http\UI\Admin\Resources\LocationAttributes\Tables\LocationAttributesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\LocationAttributes\Tables\LocationAttributesTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\LocationAttributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocationAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('attributable_type')
                    ->searchable(),
                TextColumn::make('attributable_id')
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

## File: `src\Http\UI\Admin\Resources\PersonAttributes\Pages\CreatePersonAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\Pages\CreatePersonAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePersonAttribute extends CreateRecord
{
    protected static string $resource = PersonAttributeResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\PersonAttributes\Pages\EditPersonAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\Pages\EditPersonAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPersonAttribute extends EditRecord
{
    protected static string $resource = PersonAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Person Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.person')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\PersonAttributes\Pages\ListPersonAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\Pages\ListPersonAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPersonAttributes extends ListRecords
{
    protected static string $resource = PersonAttributeResource::class;

    protected static ?string $title = 'Person Attributes';

    protected ?string $subheading = 'Attribute for person resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Person Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.person')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\PersonAttributeResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\CreatePersonAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\EditPersonAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Pages\ListPersonAttributes;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Schemas\PersonAttributeForm;
use Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Tables\PersonAttributesTable;
use Bites\Attributing\Models\PersonAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PersonAttributeResource extends Resource
{
    protected static ?string $model = PersonAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Person Attributes';

    protected static ?int $navigationSort = 43;

    public static function form(Schema $schema): Schema
    {
        return PersonAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PersonAttributesTable::configure($table);
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
            'index' => ListPersonAttributes::route('/'),
            'create' => CreatePersonAttribute::route('/create'),
            'edit' => EditPersonAttribute::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\PersonAttributes\Schemas\PersonAttributeForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\Schemas\PersonAttributeForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->columnSpanFull(),
                TextInput::make('attributable_type')
                    ->required(),
                TextInput::make('attributable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\PersonAttributes\Tables\PersonAttributesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\PersonAttributes\Tables\PersonAttributesTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\PersonAttributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('attributable_type')
                    ->searchable(),
                TextColumn::make('attributable_id')
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

## File: `src\Http\UI\Admin\Resources\ThingAttributes\Pages\CreateThingAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\Pages\CreateThingAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateThingAttribute extends CreateRecord
{
    protected static string $resource = ThingAttributeResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\ThingAttributes\Pages\EditThingAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\Pages\EditThingAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditThingAttribute extends EditRecord
{
    protected static string $resource = ThingAttributeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.thing')),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\ThingAttributes\Pages\ListThingAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\Pages\ListThingAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages;

use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThingAttributes extends ListRecords
{
    protected static string $resource = ThingAttributeResource::class;

    protected static ?string $title = 'Thing Attributes';

    protected ?string $subheading = 'Attribute for physical item resources.';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('definition')
                ->tooltip('Definitions for Thing Attributes')
                ->iconButton()
                ->icon('rimba-design')
                ->action(fn () => redirect()->route('filament.admin.resources.attribute-definitions.thing')),

        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\ThingAttributes\Schemas\ThingAttributeForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\Schemas\ThingAttributeForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThingAttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required(),
                Textarea::make('value')
                    ->columnSpanFull(),
                TextInput::make('attributable_type')
                    ->required(),
                TextInput::make('attributable_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\ThingAttributes\Tables\ThingAttributesTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\Tables\ThingAttributesTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThingAttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('attributable_type')
                    ->searchable(),
                TextColumn::make('attributable_id')
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

## File: `src\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\Admin\Resources\ThingAttributes\ThingAttributeResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes;

use BackedEnum;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\CreateThingAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\EditThingAttribute;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Pages\ListThingAttributes;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Schemas\ThingAttributeForm;
use Bites\Attributing\Http\UI\Admin\Resources\ThingAttributes\Tables\ThingAttributesTable;
use Bites\Attributing\Models\ThingAttribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ThingAttributeResource extends Resource
{
    protected static ?string $model = ThingAttribute::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'key';

    protected static string|UnitEnum|null $navigationGroup = 'Attributes';

    protected static ?string $navigationLabel = 'Thing Attributes';

    protected static ?int $navigationSort = 44;

    public static function form(Schema $schema): Schema
    {
        return ThingAttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThingAttributesTable::configure($table);
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
            'index' => ListThingAttributes::route('/'),
            'create' => CreateThingAttribute::route('/create'),
            'edit' => EditThingAttribute::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\RelationManagers\LocationAttributesRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\RelationManagers\LocationAttributesRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LocationAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'LocationAttributes';

    protected static ?string $title = 'Place Attributes';

    protected static ?string $modelLabel = 'place attribute';

    protected static ?string $pluralModelLabel = 'place attributes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. gender, serial_number, area'),

                Textarea::make('value')
                    ->label('Value')
                    ->rows(3)
                    ->placeholder('Attribute value'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\RelationManagers\PersonAttributesRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\RelationManagers\PersonAttributesRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'personAttributes';

    protected static ?string $title = 'Person Attributes';

    protected static ?string $modelLabel = 'person attribute';

    protected static ?string $pluralModelLabel = 'person attributes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. gender, nationality, height, weight, date_of_birth, education_level, driving_license, shift_pattern, jobgroup, paygrade, etc.'),

                Textarea::make('value')
                    ->label('Value')
                    ->rows(3)
                    ->placeholder('Attribute value'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}

```

---

## File: `src\Http\UI\RelationManagers\ThingAttributesRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Http\UI\RelationManagers\ThingAttributesRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Http\UI\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ThingAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'thingAttributes';

    protected static ?string $title = 'Thing Attributes';

    protected static ?string $modelLabel = 'thing attribute';

    protected static ?string $pluralModelLabel = 'thing attributes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('e.g. gender, serial_number, area'),

                Textarea::make('value')
                    ->label('Value')
                    ->rows(3)
                    ->placeholder('Attribute value'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Value')
                    ->limit(80)
                    ->wrap()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}

```

---

## File: `src\Macros\LockWhenFilledMacro.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Macros\LockWhenFilledMacro.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Macros;

use Filament\Forms\Components\Field;

final class LockWhenFilledMacro
{
    public static function register(): void
    {
        self::registerLockWhenFilled();
    }

    private static function registerLockWhenFilled(): void
    {
        // Prevent double registration
        if (method_exists(Field::class, 'lockWhenFilled')) {
            return;
        }

        Field::macro('lockWhenFilled', function (
            ?callable $bypass = null,
            bool $readOnly = false,
        ) {
            /** @var Field $this */
            return $this->afterStateHydrated(function (Field $component, $state) use ($bypass, $readOnly): void {
                // 1. Check if we should ignore the lock (e.g. for Admins)
                if (is_callable($bypass) && $bypass($component, $state)) {
                    return;
                }

                // 2. If the initial hydrated state is not blank, lock it
                if (! blank($state)) {
                    $readOnly
                        ? $component->readOnly()
                        : $component->disabled()->dehydrated(); // dehydrated keeps it in the save request
                }
            });
        });
    }
}

```

---

## File: `src\Models\AttributeDefinition.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Models\AttributeDefinition.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'family',
    'group',
    'key',
    'name',
    'description',
    'applies_to',
    'example_key',
    'example_value',
    'has_options',
    'is_active',
    'is_abac',
    'is_system',
    'sort_order',
])]
class AttributeDefinition extends Model
{
    protected function casts(): array
    {
        return [
            'applies_to' => 'array',
            'has_options' => 'boolean',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }
}

```

---

## File: `src\Models\AttributeOption.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Models\AttributeOption.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'attribute_definition_id',
    'value',
    'label',
    'is_active',
    'sort_order',
])]
class AttributeOption extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(AttributeDefinition::class, 'attribute_definition_id');
    }
}

```

---

## File: `src\Models\LocationAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Models\LocationAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'key',
    'value',
    'attributable_id',
    'attributable_type',
])]
class LocationAttribute extends Model
{
    use HasFactory;

    public function attributable()
    {
        return $this->morphTo();
    }
}

```

---

## File: `src\Models\PersonAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Models\PersonAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'key',
    'value',
    'attributable_id',
    'attributable_type',
])]
class PersonAttribute extends Model
{
    use HasFactory;

    public function attributable()
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'value' => 'encrypted',
        ];
    }
}

```

---

## File: `src\Models\ThingAttribute.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Models\ThingAttribute.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'key',
    'value',
    'attributable_id',
    'attributable_type',
])]
class ThingAttribute extends Model
{
    use HasFactory;

    public function attributable()
    {
        return $this->morphTo();
    }
}

```

---

## File: `src\Support\HasAttributeRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Support\HasAttributeRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Support;

use Bites\Attributing\Http\UI\RelationManagers\LocationAttributesRelationManager;
use Bites\Attributing\Http\UI\RelationManagers\PersonAttributesRelationManager;
use Bites\Attributing\Http\UI\RelationManagers\ThingAttributesRelationManager;
use Bites\Attributing\Traits\HasLocationAttributes;
use Bites\Attributing\Traits\HasPersonAttributes;
use Bites\Attributing\Traits\HasThingAttributes;

final class HasAttributeRelationManagers
{
    /**
     * @return array<class-string>
     */
    public static function forModel(string $modelClass): array
    {
        $traits = class_uses_recursive($modelClass);

        $relations = [];

        if (in_array(HasPersonAttributes::class, $traits, true)) {
            $relations[] = PersonAttributesRelationManager::class;
        }

        if (in_array(HasThingAttributes::class, $traits, true)) {
            $relations[] = ThingAttributesRelationManager::class;
        }

        if (in_array(HasLocationAttributes::class, $traits, true)) {
            $relations[] = LocationAttributesRelationManager::class;
        }

        return $relations;
    }
}

```

---

## File: `src\Traits\HasLocationAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Traits\HasLocationAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\LocationAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasLocationAttributes
{
    /**
     * @property Collection $LocationAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany LocationAttributes()
     */
    public function LocationAttributes(): MorphMany
    {
        return $this->morphMany(LocationAttribute::class, 'attributable');
    }
}

```

---

## File: `src\Traits\HasPersonAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Traits\HasPersonAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\PersonAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasPersonAttributes
{
    /**
     * @property Collection $personAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany personAttributes()
     */
    public function personAttributes(): MorphMany
    {
        return $this->morphMany(PersonAttribute::class, 'attributable');
    }
}

```

---

## File: `src\Traits\HasThingAttributes.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\attributing\src\Traits\HasThingAttributes.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Attributing\Traits;

use Bites\Attributing\Models\ThingAttribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasThingAttributes
{
    /**
     * @property Collection $thingAttributes
     *
     * @method \Illuminate\Database\Eloquent\Relations\MorphMany thingAttributes()
     */
    public function thingAttributes(): MorphMany
    {
        return $this->morphMany(ThingAttribute::class, 'attributable');
    }
}

```

---

