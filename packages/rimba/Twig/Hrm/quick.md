# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:20*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Hrm/src' => 'Rimba\Twig\Hrm',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020334_create_biz_hrm_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm\database\migrations\2026_06_15_020334_create_biz_hrm_tables.php`

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

        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('org_corp_id')->constrained();
            $table->enum('status', ['active', 'resigned', 'terminated', 'retired'])->default('active');
            $table->string('employee_no')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('job_titles', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('jobgrade')->nullable();
            $table->uuid('uuid')->unique();
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->string('masco_code')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_titles');
        Schema::dropIfExists('employees');
    }
};

```

---

## File: `src\HrmServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm\src\HrmServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm;

use Bites\Base\Services\BitesServiceProvider;

class HrmServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Employee.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm\src\Models\Employee.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm\Models;

use App\Trees\Organization\Models\OrgCorp;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'staff_id',
    'org_corp_id',
    'status',
    'employee_no',
    'hire_date',
    'termination_date',
    'attributes',
])]
class Employee extends Model
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
            'staff_id' => 'integer',
            'org_corp_id' => 'integer',
            'hire_date' => 'date',
            'termination_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}

```

---

## File: `src\Models\JobTitle.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm\src\Models\JobTitle.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Hrm\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'jobgrade',
    'uuid',
    'description',
    'attributes',
    'masco_code',
])]
class JobTitle extends Model
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
}

```

---

