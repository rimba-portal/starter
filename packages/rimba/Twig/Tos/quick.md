# PHP Files Code Dump
*Generated on: 2026-07-13 15:44:20*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos`*

---

## File: `src\Models\Offer.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\Models\Offer.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use App\Trees\Organization\Models\OrgTeam;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'name',
    'description',
    'attributes',
])]
class Offer extends Model
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

    public function offerCategoryAssignments(): HasMany
    {
        return $this->hasMany(OfferCategoryAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\OfferCategory.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\Models\OfferCategory.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

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
class OfferCategory extends Model
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
        return $this->hasMany(OfferCategory::class);
    }

    public function offerCategoryAssignments(): HasMany
    {
        return $this->hasMany(OfferCategoryAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class);
    }
}

```

---

## File: `src\Models\OfferCategoryAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\Models\OfferCategoryAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'offer_id',
    'offer_category_id',
    'attributes',
])]
class OfferCategoryAssignment extends Model
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
            'offer_id' => 'integer',
            'offer_category_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function offer(): BelongsTo
    {
        return $this->belongsTo(Offer::class);
    }

    public function offerCategory(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class);
    }
}

```

---

## File: `src\Models\Request.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\Models\Request.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Repo\App\Process\Models\WorkflowInstance;

#[Fillable([
    'requester_id',
    'workflow_instance_id',
    'status',
    'name',
    'description',
    'request_type',
    'attributes',
])]
class Request extends Model
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
            'requester_id' => 'integer',
            'workflow_instance_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function ref(): MorphTo
    {
        return $this->morphTo();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function workflowInstance(): BelongsTo
    {
        return $this->belongsTo(WorkflowInstance::class);
    }
}

```

---

## File: `src\Models\RequestType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\Models\RequestType.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Repo\App\Process\Models\Workflow;

#[Fillable([
    'workflow_id',
    'name',
    'attributes',
])]
class RequestType extends Model
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
            'workflow_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}

```

---

## File: `src\TosServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Tos\src\TosServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Tos;

use App\Services\BitesServiceProvider;

class TosServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

