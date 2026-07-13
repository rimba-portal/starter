# PHP Files Code Dump
*Generated on: 2026-07-13 07:38:50*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam`*

---

## File: `src\EamServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Eam\src\EamServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Eam;

use App\Services\BitesServiceProvider;

class EamServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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

use App\Trees\FloorPlan\Models\Location;
use App\Trees\Organization\Models\OrgTeam;
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

