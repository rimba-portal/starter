# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:24*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Lms/src' => 'Rimba\Twig\Lms',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020338_create_biz_lms_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\database\migrations\2026_06_15_020338_create_biz_lms_table.php`

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

        Schema::create('courses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->constrained();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_groups', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('course_groups');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_group_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('course_group_id')->constrained();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('modules', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('validity_days')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('course_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('materials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_team_id')->nullable()->constrained();
            $table->enum('type', ['document', 'video', 'link', 'other'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('material_modules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('material_id')->constrained();
            $table->foreignId('module_id')->constrained();
            $table->integer('sequence')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quizzes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('pass_score')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('quiz_attempts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('quiz_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->integer('score')->nullable();
            $table->timestamp('attempted_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('evaluations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->nullable()->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('evaluator_id')->nullable()->constrained('users');
            $table->enum('result', ['pass', 'fail'])->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('certificates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('module_id')->constrained();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('quiz_attempt_id')->nullable()->constrained();
            $table->foreignId('evaluation_id')->nullable()->constrained();
            $table->foreignId('issued_by')->nullable()->constrained('users');
            $table->enum('status', ['valid', 'expired', 'revoked'])->default('valid');
            $table->timestamp('issued_at')->nullable();
            $table->timestamp('expires_at')->nullable();
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
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quizzes');
        Schema::dropIfExists('material_modules');
        Schema::dropIfExists('materials');
        Schema::dropIfExists('course_modules');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('course_groups');
        Schema::dropIfExists('courses');
        Schema::dropIfExists('courses');
    }
};

```

---

## File: `src\LmsServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\LmsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms;

use Bites\Base\Services\BitesServiceProvider;

class LmsServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Certificate.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Certificate.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Models\User;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'module_id',
    'staff_id',
    'quiz_attempt_id',
    'evaluation_id',
    'issued_by',
    'status',
    'issued_at',
    'expires_at',
    'attributes',
])]
class Certificate extends Model
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
            'module_id' => 'integer',
            'staff_id' => 'integer',
            'quiz_attempt_id' => 'integer',
            'evaluation_id' => 'integer',
            'issued_by' => 'integer',
            'issued_at' => 'timestamp',
            'expires_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function issuedBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

```

---

## File: `src\Models\Course.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Course.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\OrgTeam;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'code',
    'title',
    'description',
    'is_active',
    'attributes',
])]
class Course extends Model
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
            'is_active' => 'boolean',
            'attributes' => 'array',
        ];
    }

    public function courseModules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function courseGroupAssignments(): HasMany
    {
        return $this->hasMany(CourseGroupAssignment::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\CourseGroup.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseGroup.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

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
class CourseGroup extends Model
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
        return $this->hasMany(CourseGroup::class);
    }

    public function courseGroupAssignments(): HasMany
    {
        return $this->hasMany(CourseGroupAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(CourseGroup::class);
    }
}

```

---

## File: `src\Models\CourseGroupAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseGroupAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id',
    'course_group_id',
    'attributes',
])]
class CourseGroupAssignment extends Model
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
            'course_id' => 'integer',
            'course_group_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function courseGroup(): BelongsTo
    {
        return $this->belongsTo(CourseGroup::class);
    }
}

```

---

## File: `src\Models\CourseModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\CourseModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'course_id',
    'module_id',
    'sequence',
    'attributes',
])]
class CourseModule extends Model
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
            'course_id' => 'integer',
            'module_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}

```

---

## File: `src\Models\Evaluation.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Evaluation.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Models\User;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_id',
    'staff_id',
    'evaluator_id',
    'result',
    'evaluated_at',
    'attributes',
])]
class Evaluation extends Model
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
            'module_id' => 'integer',
            'staff_id' => 'integer',
            'evaluator_id' => 'integer',
            'evaluated_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

```

---

## File: `src\Models\Material.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Material.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\OrgTeam;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'org_team_id',
    'type',
    'name',
    'description',
    'attributes',
])]
class Material extends Model
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

    public function materialModules(): HasMany
    {
        return $this->hasMany(MaterialModule::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\MaterialModule.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\MaterialModule.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'material_id',
    'module_id',
    'sequence',
    'attributes',
])]
class MaterialModule extends Model
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
            'material_id' => 'integer',
            'module_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}

```

---

## File: `src\Models\Module.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Module.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'description',
    'duration_minutes',
    'validity_days',
    'attributes',
])]
class Module extends Model
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

    public function courseModules(): HasMany
    {
        return $this->hasMany(CourseModule::class);
    }

    public function materialModules(): HasMany
    {
        return $this->hasMany(MaterialModule::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
}

```

---

## File: `src\Models\Quiz.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\Quiz.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'module_id',
    'name',
    'description',
    'pass_score',
    'attributes',
])]
class Quiz extends Model
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
            'module_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }
}

```

---

## File: `src\Models\QuizAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lms\src\Models\QuizAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lms\Models;

use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'quiz_id',
    'staff_id',
    'result',
    'score',
    'attempted_at',
    'attributes',
])]
class QuizAttempt extends Model
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
            'quiz_id' => 'integer',
            'staff_id' => 'integer',
            'attempted_at' => 'timestamp',
            'attributes' => 'array',
        ];
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

```

---

