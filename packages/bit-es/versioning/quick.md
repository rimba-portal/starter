# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:08*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\versioning`*

---

## File: `config\ver.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\config\ver.php`

```php
<?php

declare(strict_types=1);

return [
    'default_status' => 'draft',
];

```

---

## File: `database\migrations\0002_01_01_000102_create_versions_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\database\migrations\0002_01_01_000102_create_versions_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('versions', function (Blueprint $table): void {
            $table->id();

            $table->morphs('versionable');

            $table->string('version');
            $table->unsignedInteger('major');
            $table->unsignedInteger('minor');
            $table->unsignedInteger('patch');

            $table->string('status')->default('draft');

            $table->string('content_type')->nullable();
            $table->text('content_url');

            $table->string('checksum')->nullable();

            $table->timestamp('effective_from')->nullable();
            $table->timestamp('effective_until')->nullable();
            $table->timestamp('released_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};

```

---

## File: `src\Actions\CreateVersion.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Actions\CreateVersion.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Enums\VersionIncrementType;
use Bites\Versioning\Models\Version;
use Bites\Versioning\Services\SemanticVersionService;
use Illuminate\Database\Eloquent\Model;

class CreateVersion
{
    public function __construct(
        protected SemanticVersionService $semanticVersionService,
    ) {}

    public static function make(Model $model): CreateVersionBuilder
    {
        return new CreateVersionBuilder($model);
    }

    public function execute(
        Model $model,
        VersionIncrementType $increment = VersionIncrementType::Major,
        array $attributes = [],
    ): Version {

        $latest = $model->latestVersion();

        if (! $latest) {

            return $model->versions()->create([
                'version' => '0.0.0',
                'major' => 0,
                'minor' => 0,
                'patch' => 0,
                ...$attributes,
            ]);
        }

        [$major, $minor, $patch] =
            match ($increment) {
                VersionIncrementType::Patch => $this
                    ->semanticVersionService
                    ->incrementPatch(
                        $latest->major,
                        $latest->minor,
                        $latest->patch
                    ),

                VersionIncrementType::Minor => $this
                    ->semanticVersionService
                    ->incrementMinor(
                        $latest->major,
                        $latest->minor
                    ),

                VersionIncrementType::Major => $this
                    ->semanticVersionService
                    ->incrementMajor(
                        $latest->major
                    ),
            };

        return $model->versions()->create([
            'version' => sprintf('%s.%s.%s', $major, $minor, $patch),
            'major' => $major,
            'minor' => $minor,
            'patch' => $patch,
            ...$attributes,
        ]);
    }
}

```

---

## File: `src\Actions\GenerateNextVersion.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Actions\GenerateNextVersion.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Models\Version;
use Bites\Versioning\Services\SemanticVersionService;

class GenerateNextVersion
{
    public function __construct(
        protected SemanticVersionService $service
    ) {}

    public function patch(
        Version $version
    ): string {

        [$major, $minor, $patch] =
            $this->service->incrementPatch(
                $version->major,
                $version->minor,
                $version->patch
            );

        return sprintf('%s.%s.%s', $major, $minor, $patch);
    }
}

```

---

## File: `src\Actions\ReleaseVersion.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Actions\ReleaseVersion.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Actions;

use Bites\Versioning\Enums\VersionStatus;
use Bites\Versioning\Models\Version;

class ReleaseVersion
{
    public function execute(
        Version $version
    ): Version {

        $version->update([
            'status' => VersionStatus::Released,
            'released_at' => now(),
        ]);

        return $version;
    }
}

```

---

## File: `src\Builders\MakeVersionBuilder.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Builders\MakeVersionBuilder.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Builders;

use Bites\Versioning\Enums\VersionIncrementType;
use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Model;

class MakeVersionBuilder
{
    public $semanticVersionService;

    protected VersionIncrementType $increment =
        VersionIncrementType::Major;

    protected ?string $contentUrl = null;

    protected bool $release = false;

    public function __construct(
        protected Model $model
    ) {}

    public function major(): static
    {
        $this->increment =
            VersionIncrementType::Major;

        return $this;
    }

    public function minor(): static
    {
        $this->increment =
            VersionIncrementType::Minor;

        return $this;
    }

    public function patch(): static
    {
        $this->increment =
            VersionIncrementType::Patch;

        return $this;
    }

    public function url(string $url): static
    {
        $this->contentUrl = $url;

        return $this;
    }

    public function release(): static
    {
        $this->release = true;

        return $this;
    }

    public function execute(): Version
    {
        if (! $latest) {
            return $this->model->versions()->create([
                'version' => '0.0.0',
            ]);
        }

        [$major, $minor, $patch] = match ($this->increment) {

            VersionIncrementType::Major => $this->semanticVersionService->incrementMajor(
                $latest->major
            ),

            VersionIncrementType::Minor => $this->semanticVersionService->incrementMinor(
                $latest->major,
                $latest->minor
            ),

            VersionIncrementType::Patch => $this->semanticVersionService->incrementPatch(
                $latest->major,
                $latest->minor,
                $latest->patch
            ),
        };
    }
}

```

---

## File: `src\Builders\VersionBuilder.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Builders\VersionBuilder.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Builders;

use Illuminate\Database\Eloquent\Builder;

class VersionBuilder extends Builder
{
    public function latestVersion(): static
    {
        return $this
            ->orderByDesc('major')
            ->orderByDesc('minor')
            ->orderByDesc('patch');
    }

    public function major(
        int $major
    ): static {
        return $this->where('major', $major);
    }

    public function minor(
        int $major,
        int $minor
    ): static {
        return $this
            ->where('major', $major)
            ->where('minor', $minor);
    }

    public function patch(
        int $major,
        int $minor,
        int $patch
    ): static {
        return $this
            ->where('major', $major)
            ->where('minor', $minor)
            ->where('patch', $patch);
    }

    public function released(): static
    {
        return $this->where('status', 'released');
    }

    public function draft(): static
    {
        return $this->where('status', 'draft');
    }

    public function current(): static
    {
        return $this
            ->released()
            ->effective();
    }

    public function review(): static
    {
        return $this->where('status', 'review');
    }

    public function approved(): static
    {
        return $this->where('status', 'approved');
    }

    public function obsolete(): static
    {
        return $this->where('status', 'obsolete');
    }

    public function archived(): static
    {
        return $this->where('status', 'archived');
    }

    public function effective(): static
    {
        return $this
            ->where('effective_from', '<=', now())
            ->where(function ($query): void {
                $query
                    ->whereNull('effective_until')
                    ->orWhere(
                        'effective_until',
                        '>',
                        now()
                    );
            });
    }
}

```

---

## File: `src\Enums\VersionIncrementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Enums\VersionIncrementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Enums;

enum VersionIncrementType: string
{
    case Major = 'major';
    case Minor = 'minor';
    case Patch = 'patch';
}

```

---

## File: `src\Enums\VersionStatus.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Enums\VersionStatus.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Enums;

enum VersionStatus: string
{
    case Draft = 'draft';
    case Review = 'review';
    case Approved = 'approved';
    case Released = 'released';
    case Obsolete = 'obsolete';
    case Archived = 'archived';
}

```

---

## File: `src\Http\UI\Admin\Resources\VersionResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Http\UI\Admin\Resources\VersionResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions;

use Bites\Versioning\Models\Version;
use Filament\Forms\Form;
use Filament\Resources\Resource;

class VersionResource extends Resource
{
    protected static ?string $model =
        Version::class;

    public static function form(
        Form $form
    ): Form {
        return $form->schema([

            TextInput::make('version')
                ->required(),

            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'review' => 'Review',
                    'approved' => 'Approved',
                    'released' => 'Released',
                    'obsolete' => 'Obsolete',
                    'archived' => 'Archived',
                ]),

            TextInput::make('content_url')
                ->url(),

            DateTimePicker::make(
                'effective_from'
            ),

            DateTimePicker::make(
                'effective_until'
            ),

            MarkdownEditor::make('notes'),
        ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers;

use Bites\Versioning\Enums\VersionStatus;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->options(VersionStatus::class)
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
                    ->options(VersionStatus::class),
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

```

---

## File: `src\Models\Version.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Models\Version.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Models;

use Bites\Versioning\Builders\VersionBuilder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'versionable_type',
    'versionable_id',
    'version',
    'major',
    'minor',
    'patch',
    'status',
    'content_type',
    'content_url',
    'checksum',
    'effective_from',
    'effective_until',
    'released_at',
    'notes',
])]
class Version extends Model
{
    public function newEloquentBuilder($query): VersionBuilder
    {
        return new VersionBuilder($query);
    }

    public function versionable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'effective_from' => 'datetime',
            'effective_until' => 'datetime',
            'released_at' => 'datetime',
        ];
    }
}

```

---

## File: `src\Services\SemanticVersionService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Services\SemanticVersionService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Services;

class SemanticVersionService
{
    public function incrementPatch(
        int $major,
        int $minor,
        int $patch
    ): array {
        return [
            $major,
            $minor,
            $patch + 1,
        ];
    }

    public function incrementMinor(
        int $major,
        int $minor
    ): array {
        return [
            $major,
            $minor + 1,
            0,
        ];
    }

    public function incrementMajor(
        int $major
    ): array {
        return [
            $major + 1,
            0,
            0,
        ];
    }

    public function format(
        int $major,
        int $minor,
        int $patch,
    ): string {
        return sprintf('%d.%d.%d', $major, $minor, $patch);
    }

    public function parse(
        string $version
    ): array {
        [$major, $minor, $patch] =
            explode('.', $version);

        return [
            (int) $major,
            (int) $minor,
            (int) $patch,
        ];
    }
}

```

---

## File: `src\Services\VersionResolverService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Services\VersionResolverService.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Services;

class VersionResolverService
{
    public function current($model)
    {
        return $model->versions()
            ->where('status', 'released')
            ->latest()
            ->first();
    }
}

```

---

## File: `src\Traits\HasVersions.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Traits\HasVersions.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Traits;

use Bites\Versioning\Models\Version;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasVersions
{
    public function versions(): MorphMany
    {
        return $this->morphMany(
            Version::class,
            'versionable'
        );
    }

    public function currentVersion(): ?Version
    {
        return $this->versions()
            ->current()
            ->latest('released_at')
            ->first();
    }

    public function latestVersion(): ?Version
    {
        return $this->versions()
            ->latest('id')
            ->first();
    }
}

```

---

## File: `src\Traits\ResourceHasVersionRelations.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\Traits\ResourceHasVersionRelations.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning\Traits;

use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;

trait ResourceHasVersionRelations
{
    public static function getRelations(): array
    {
        // Fallback check to avoid conflicts if parent or local defines relations
        $localRelations = method_exists(self::class, 'getRelations') ? self::getRelations() : [];

        return array_merge($localRelations, [
            VersionsRelationManager::class,
        ]);
    }
}

```

---

## File: `src\VersioningServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\versioning\src\VersioningServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Versioning;

use Bites\Base\Services\BitesServiceProvider;
use Bites\Versioning\Http\UI\Admin\Resources\Versions\RelationManagers\VersionsRelationManager;
use Bites\Versioning\Traits\HasVersions;
use Filament\Facades\Filament as FacadesFilament;

class VersioningServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Intercept the Filament execution pipeline safely before panels render
        FacadesFilament::serving(function (): void {
            foreach (FacadesFilament::getPanels() as $panel) {
                foreach ($panel->getResources() as $resourceClass) {
                    $model = $resourceClass::getModel();

                    // Check if the underlying Eloquent model uses your Rimba Tree / Bites trait
                    if (in_array(HasVersions::class, class_uses_recursive($model))) {
                        // dd($resourceClass::getRelations());
                        // Safely inject your relation manager using Filament's internal pipeline hook
                        // $resourceClass::appendRelationManagers([
                        // VersionsRelationManager::class,
                        // ]);
                    }
                }
            }
        });
    }
}

```

---

