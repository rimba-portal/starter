# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:30*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Hrm`*

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
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
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

