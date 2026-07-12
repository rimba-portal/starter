# PHP Files Code Dump
*Generated on: 2026-07-12 12:51:56*
*Target Folder: `\starter\packages\rimba\Twig\Lcs`*

---

## File: `src\LcsServiceProvider.php`
**Absolute Path:** `\starter\packages\rimba\Twig\Lcs\src\LcsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lcs;

use App\Services\BitesServiceProvider;

class LcsServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\ContractConfidentiality.php`
**Absolute Path:** `\starter\packages\rimba\Twig\Lcs\src\Models\ContractConfidentiality.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lcs\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'contract_id',
    'payload',
    'allowed_roles',
    'meta',
])]
class ContractConfidentiality extends Model
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
            'allowed_roles' => 'array',
            'meta' => 'array',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

```

---

