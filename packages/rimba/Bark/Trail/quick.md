# PHP Files Code Dump
*Generated on: 2026-07-14 16:20:49*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Trail`*

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
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

