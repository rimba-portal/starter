# PHP Files Code Dump
*Generated on: 2026-07-13 07:38:29*
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

