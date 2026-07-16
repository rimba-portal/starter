# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:03*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Bark/Trail/src' => 'Rimba\Bark\Trail',
        ],
    ],
];

```

---

## File: `database\migrations\0002_01_01_000605_create_audit_logs_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail\database\migrations\0002_01_01_000605_create_audit_logs_table.php`

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

        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('staff_id')->nullable()->constrained();
            $table->string('result')->nullable();
            $table->string('actor');
            $table->string('action');
            $table->string('reason')->nullable();
            $table->json('metadata')->nullable();
            $table->morphs('ref');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

```

---

## File: `src\Models\AuditLog.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail\src\Models\AuditLog.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Trail\Models;

use App\Models\User;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'user_id',
    'staff_id',
    'result',
    'actor',
    'action',
    'reason',
    'metadata',
])]
class AuditLog extends Model
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
            'user_id' => 'integer',
            'staff_id' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function ref(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

```

---

## File: `src\TrailServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail\src\TrailServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Trail;

use Bites\Base\Services\BitesServiceProvider;

class TrailServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

