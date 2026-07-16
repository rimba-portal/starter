# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:22*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lcs`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lcs\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Twig/Lcs/src' => 'Rimba\Twig\Lcs',
        ],
    ],
];

```

---

## File: `database\migrations\2026_06_15_020336_create_biz_lcs_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lcs\database\migrations\2026_06_15_020336_create_biz_lcs_tables.php`

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

        Schema::create('contract_confidentialities', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('contract_id')->constrained()->unique();
            $table->string('payload');
            $table->json('allowed_roles')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contract_confidentialities');
    }
};

```

---

## File: `src\LcsServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lcs\src\LcsServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Twig\Lcs;

use Bites\Base\Services\BitesServiceProvider;

class LcsServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\ContractConfidentiality.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Twig\Lcs\src\Models\ContractConfidentiality.php`

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

