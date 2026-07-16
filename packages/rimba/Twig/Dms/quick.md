# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:16*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Dms/src' => 'Rimba\Twig\Dms',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020335_create_biz_dms_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\database\migrations\2026_06_15_020335_create_biz_dms_table.php`

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

        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->foreignId('org_unit_id')->nullable()->constrained();
            $table->foreignId('location_id')->nullable()->constrained();
            $table->enum('type', ['sop', 'work_instruction', 'policy', 'drawing', 'contract', 'other'])->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('document_categories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('document_categories');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('document_category_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('document_id')->constrained();
            $table->foreignId('document_category_id')->constrained();
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
        Schema::dropIfExists('document_category_assignments');
        Schema::dropIfExists('offer_category_assignments');
        Schema::dropIfExists('documents');
    }
};

```

---

## File: `src\DmsServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\src\DmsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms;

use Bites\Base\Services\BitesServiceProvider;

class DmsServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Document.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\src\Models\Document.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms\Models;

use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\OrgUnit;
use Bites\FloorPlan\Models\Location;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'org_unit_id',
    'location_id',
    'type',
    'title',
    'description',
    'attributes',
])]
class Document extends Model
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
            'org_unit_id' => 'integer',
            'location_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function documentCategoryAssignments(): HasMany
    {
        return $this->hasMany(DocumentCategoryAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}

```

---

## File: `src\Models\DocumentCategory.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\src\Models\DocumentCategory.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms\Models;

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
class DocumentCategory extends Model
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
        return $this->hasMany(DocumentCategory::class);
    }

    public function documentCategoryAssignments(): HasMany
    {
        return $this->hasMany(DocumentCategoryAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }
}

```

---

## File: `src\Models\DocumentCategoryAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Dms\src\Models\DocumentCategoryAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Dms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'document_id',
    'document_category_id',
    'attributes',
])]
class DocumentCategoryAssignment extends Model
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
            'document_id' => 'integer',
            'document_category_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function documentCategory(): BelongsTo
    {
        return $this->belongsTo(DocumentCategory::class);
    }
}

```

---

