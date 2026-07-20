# PHP Files Code Dump
*Generated on: 2026-07-20 15:38:05*
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
    public function up(): void
    {
        Schema::create('qms_documents', function (Blueprint $table): void {
            $table->id();

            // 1. QMS Hierarchy & Relationships
            // $table->foreignId('qms_tier_id')->constrained('qms_tiers')->onDelete('restrict');
            $table->foreignId('parent_id')->nullable()->constrained('qms_documents')->onDelete('set null'); // SOP -> WI link

            // 2. Core Identification & Taxonomy
            $table->string('doc_number')->unique(); // Unique identifier (e.g., SOP-QA-001)
            $table->string('title');
            $table->string('site_location')->nullable(); // Multi-plant/site traceability

            // 3. Strict Lifecycle & State Management
            // States: draft, in_review, active, obsolete, archived
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('is_controlled')->default(true); // Controlled copies vs uncontrolled documents

            // 4. Structural Stakeholder Ownership
            $table->foreignId('team_id')->constrained('org_teams')->onDelete('restrict'); // Process Owner (Ultimate Responsible Party)
            $table->foreignId('author_id')->constrained('staff')->onDelete('restrict'); // Author/Preparer

            // 5. Versioning Pointer (Polymorphic target link)
            $table->foreignId('current_version_id')->nullable()->constrained('versions')->onDelete('set null');

            // 6. QMS Regulatory / Compliance Metadata
            $table->string('security_classification')->default('internal'); // public, internal, restricted, highly_confidential
            $table->string('regulatory_impact')->nullable(); // e.g., ISO9001:2015 Clause 7.5, FDA 21 CFR Part 11
            $table->json('risk_assessment_tags')->nullable(); // Stores associated risk matrix IDs or tags

            // 7. Dynamic Retention & Expiry Controls (Crucial for Audits)
            $table->unsignedInteger('retention_period_years')->default(5); // How long the file must be legally preserved
            $table->date('effective_date')->nullable(); // The official rollout date to the shop floor
            $table->date('next_review_date')->nullable(); // Mandatory routine system review milestone

            // 8. Electronic Signature Validation Pointers
            $table->string('regulatory_hash', 64)->nullable(); // SHA-256 validation snapshot string for FDA compliance

            // 9. Standard Timestamps & soft deletes (Never hard-delete a QMS file!)
            $table->timestamps();
            $table->softDeletes();

            // Multi-column Indexes for lightning-fast DMS cross-referencing
            $table->index(['team_id', 'status']);
            $table->index(['next_review_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qms_documents');
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
use App\Trees\Organization\Models\Staff;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'parent_id', 'doc_number', 'title',
    'site_location', 'status', 'is_controlled', 'team_id', 'author_id',
    'current_version_id', 'security_classification', 'regulatory_impact',
    'risk_assessment_tags', 'retention_period_years', 'effective_date',
    'next_review_date', 'regulatory_hash',
])]
class Document extends Model
{
    use HasVersions;
    use SoftDeletes;
    /**
     * Relationship to the specific structural QMS system hierarchy tier.
     */
    // public function tier(): BelongsTo
    // {
    //     return $this->belongsTo(QmsTier::class, 'qms_tier_id');
    // }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class, 'owner_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'author_id');
    }

    protected function casts(): array
    {
        return [
            'is_controlled' => 'boolean',
            'risk_assessment_tags' => 'array', // Handles flexible JSON tags dynamically
            'effective_date' => 'date',
            'next_review_date' => 'date',
            'retention_period_years' => 'integer',
        ];
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

