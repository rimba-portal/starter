# PHP Files Code Dump
*Generated on: 2026-07-13 07:38:37*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\agreement`*

---

## File: `database\migrations\0002_01_01_000602_create_agreements_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\database\migrations\0002_01_01_000602_create_agreements_tables.php`

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

        Schema::create('agreement_types', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->longText('template')->nullable();
            $table->json('public_schema')->nullable();
            $table->json('confidential_schema')->nullable();
            $table->json('notify')->nullable();
            $table->integer('expiry_notify_days')->default(30);
            $table->boolean('requires_approval')->default(false);
            $table->boolean('requires_signature')->default(false);
            $table->foreignId('workflow_id')->nullable()->constrained();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
        Schema::create('agreements', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('agreement_type');
            $table->string('contract_no')->nullable();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->enum('status', ['draft', 'pending', 'active', 'expired', 'terminated', 'archived'])->default('draft');
            $table->json('terms')->nullable();
            $table->json('meta')->nullable();
            // $table->morphs('contractable');
            $table->timestamps();
        });
        Schema::create('parties', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('agreement_id')->constrained('agreements');
            $table->string('role')->nullable();
            $table->boolean('is_signatory')->default(false);
            $table->boolean('notify_on_expiry')->default(true);
            $table->json('meta')->nullable();
            $table->morphs('party');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_parties');
        Schema::dropIfExists('contracts');
        Schema::dropIfExists('contract_types');
    }
};

```

---

## File: `src\AgreementServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\AgreementServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement;

use App\Services\BitesServiceProvider;

class AgreementServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

    }
}

```

---

## File: `src\Models\Agreement.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\Agreement.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use App\Trees\Organization\Models\OrgCorp;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'uuid',
    'agreement_type',
    'org_corp_id',
    'contract_no',
    'title',
    'summary',
    'start_date',
    'end_date',
    'renewal_date',
    'status',
    'terms',
    'meta',
])]
class Agreement extends Model
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
            'org_corp_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'renewal_date' => 'date',
            'terms' => 'array',
            'meta' => 'array',
        ];
    }

    public function agreementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function parties(): HasMany
    {
        return $this->hasMany(Party::class);
    }

    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}

```

---

## File: `src\Models\AgreementType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\AgreementType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Repo\App\Process\Models\Workflow;

#[Fillable([
    'uuid',
    'name',
    'code',
    'description',
    'template',
    'public_schema',
    'confidential_schema',
    'notify',
    'expiry_notify_days',
    'requires_approval',
    'requires_signature',
    'workflow_id',
    'meta',
])]
class AgreementType extends Model
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
            'public_schema' => 'array',
            'confidential_schema' => 'array',
            'notify' => 'array',
            'requires_approval' => 'boolean',
            'requires_signature' => 'boolean',
            'workflow_id' => 'integer',
            'meta' => 'array',
        ];
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }
}

```

---

## File: `src\Models\Party.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\agreement\src\Models\Party.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Agreement\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'contract_id',
    'role',
    'is_signatory',
    'notify_on_expiry',
    'meta',
])]
class Party extends Model
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
            'contract_id' => 'integer',
            'is_signatory' => 'boolean',
            'notify_on_expiry' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function party(): MorphTo
    {
        return $this->morphTo();
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }
}

```

---

