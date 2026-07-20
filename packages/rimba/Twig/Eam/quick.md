# PHP Files Code Dump
*Generated on: 2026-07-20 15:38:07*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Eam/src' => 'Rimba\Twig\Eam',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020349_create_biz_eam_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\database\migrations\2026_06_15_020349_create_biz_eam_table.php`

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

        Schema::create('assets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['machine', 'tool', 'vehicle', 'storage', 'facility', 'other'])->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('serial_number')->nullable();
            $table->enum('status', ['setup', 'active', 'maintenance', 'out_of_service', 'disposed'])->default('setup');
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('asset_types', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('asset_type_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained();
            $table->foreignId('asset_type_id')->constrained();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('asset_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('asset_id')->constrained();
            $table->enum('type', ['primary', 'secondary', 'temporary'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('attributes')->nullable();
            $table->morphs('assignable');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_assignments');
        Schema::dropIfExists('asset_type_assignments');
        Schema::dropIfExists('asset_types');
        Schema::dropIfExists('assets');
    }
};

```

---

## File: `src\EamServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\EamServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam;

use Bites\Base\Services\BitesServiceProvider;

class EamServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Asset.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\Models\Asset.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam\Models;

use App\Trees\Organization\Models\OrgTeam;
use Bites\FloorPlan\Models\Location;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Rimba\Twig\Tos\Models\Request;

#[Fillable([
    'org_team_id',
    'location_id',
    'code',
    'name',
    'description',
    'type',
    'brand',
    'model',
    'serial_number',
    'status',
    'attributes',
])]
class Asset extends Model
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
            'location_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function assetAssignments(): HasMany
    {
        return $this->hasMany(AssetAssignment::class);
    }

    public function refs(): MorphMany
    {
        return $this->morphMany(Request::class, 'refable');
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}

```

---

## File: `src\Models\AssetAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\Models\AssetAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'asset_id',
    'type',
    'start_date',
    'end_date',
    'attributes',
])]
class AssetAssignment extends Model
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
            'asset_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}

```

---

## File: `src\Models\AssetType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\Models\AssetType.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'attributes',
])]
class AssetType extends Model
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

    public function assetTypeAssignments(): HasMany
    {
        return $this->hasMany(AssetTypeAssignment::class);
    }
}

```

---

## File: `src\Models\AssetTypeAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\Models\AssetTypeAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'asset_id',
    'asset_type_id',
    'attributes',
])]
class AssetTypeAssignment extends Model
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
            'asset_id' => 'integer',
            'asset_type_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function assetType(): BelongsTo
    {
        return $this->belongsTo(AssetType::class);
    }
}

```

---

