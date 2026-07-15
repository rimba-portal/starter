# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:04*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\identity`*

---

## File: `config\identity.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\config\identity.php`

```php
<?php

declare(strict_types=1);

use Rimba\Identity\Factors\FaceFactor;
use Rimba\Identity\Factors\PinFactor;

return [

    'guard' => 'web',

    'staff_id_column' => 'staff_id',

    'face_threshold' => 0.50,

    'pipeline' => [
        'face',
        'pin',
    ],

    'drivers' => [

        'face' => FaceFactor::class,

        'pin' => PinFactor::class,

    ],

];

```

---

## File: `database\migrations\0002_01_01_000201_create_identity_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\database\migrations\0002_01_01_000201_create_identity_tables.php`

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

        Schema::create('identity_profiles', function (Blueprint $table): void {
            $table->id();
            $table->morphs('personable');
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('identity_credentials', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('identity_profile_id')->constrained()->cascadeOnDelete();
            $table->string('factor_type');
            $table->longText('value');
            $table->json('metadata')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('identity_attempts', function (Blueprint $table): void {

            $table->id();
            $table->foreignId('identity_profile_id')->nullable()->constrained()->nullOnDelete();
            $table->string('factor');
            $table->string('status');
            $table->json('context')->nullable();
            $table->timestamp('attempted_at');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identity_attempts');
        Schema::dropIfExists('identity_credentials');
        Schema::dropIfExists('identity_profiles');
    }
};

```

---

## File: `src\Contracts\AuthFactor.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Contracts\AuthFactor.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Contracts;

use Rimba\Identity\Models\IdentityProfile;

interface AuthFactor
{
    public function name(): string;

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool;
}

```

---

## File: `src\Factors\FaceFactor.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Factors\FaceFactor.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Factors;

use Rimba\Identity\Contracts\AuthFactor;
use Rimba\Identity\Models\IdentityProfile;

class FaceFactor implements AuthFactor
{
    public function name(): string
    {
        return 'face';
    }

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        $distance = $payload['distance'];

        return $distance <= config(
            'identity.face_threshold',
            0.50
        );
    }
}

```

---

## File: `src\Factors\PinFactor.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Factors\PinFactor.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Factors;

use Illuminate\Support\Facades\Hash;
use Rimba\Identity\Contracts\AuthFactor;
use Rimba\Identity\Models\IdentityProfile;

class PinFactor implements AuthFactor
{
    public function name(): string
    {
        return 'pin';
    }

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        $credential = $profile
            ->credentials()
            ->where('factor_type', 'pin')
            ->first();

        if (! $credential) {
            return false;
        }

        return Hash::check(
            $payload['pin'],
            $credential->value
        );
    }
}

```

---

## File: `src\IdentityServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\IdentityServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity;

use Illuminate\Support\ServiceProvider;
use Rimba\Identity\Managers\IdentityManager;

class IdentityServiceProvider extends ServiceProvider
{
    public function registerPackage(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/identity.php',
            'identity'
        );

        $this->app->singleton(
            function (): IdentityManager {

                $identityManager = new IdentityManager;

                foreach (
                    config('identity.drivers') as $name => $driver
                ) {

                    $identityManager->extend(
                        $name,
                        $driver
                    );
                }

                return $identityManager;
            }
        );
    }

    public function bootPackage(): void
    {
        $this->publishes([
            __DIR__.'/../config/identity.php' => config_path('identity.php'),
        ], 'identity-config');

        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );
    }
}

```

---

## File: `src\Managers\IdentityManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Managers\IdentityManager.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Managers;

use InvalidArgumentException;

class IdentityManager
{
    protected array $drivers = [];

    public function extend(
        string $name,
        string $driver
    ): void {

        $this->drivers[$name] = $driver;
    }

    public function driver(
        string $name
    ) {

        if (! isset($this->drivers[$name])) {
            throw new InvalidArgumentException(
                sprintf('Driver [%s] not registered.', $name)
            );
        }

        return app(
            $this->drivers[$name]
        );
    }
}

```

---

## File: `src\Managers\PipelineManager.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Managers\PipelineManager.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Managers;

use Rimba\Identity\Models\IdentityProfile;

class PipelineManager
{
    public function __construct(
        protected IdentityManager $identity
    ) {}

    public function verify(
        IdentityProfile $profile,
        array $payload
    ): bool {

        foreach (
            config('identity.pipeline', []) as $factor
        ) {

            $driver = $this->identity->driver(
                $factor
            );

            if (! $driver->verify(
                $profile,
                $payload[$factor] ?? []
            )) {

                return false;
            }
        }

        return true;
    }
}

```

---

## File: `src\Models\IdentityAttempt.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Models\IdentityAttempt.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'identity_profile_id',
    'factor',
    'status',
    'context',
    'attempted_at',
])]
class IdentityAttempt extends Model
{
    protected function casts(): array
    {
        return [
            'context' => 'array',
            'attempted_at' => 'datetime',
        ];
    }
}

```

---

## File: `src\Models\IdentityCredential.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Models\IdentityCredential.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'identity_profile_id',
    'factor_type',
    'value',
    'metadata',
    'is_enabled',
])]
class IdentityCredential extends Model
{
    public function profile(): BelongsTo
    {
        return $this->belongsTo(
            IdentityProfile::class
        );
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_enabled' => 'boolean',
        ];
    }
}

```

---

## File: `src\Models\IdentityProfile.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Models\IdentityProfile.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'is_enabled',
])]
class IdentityProfile extends Model
{
    public function personable(): MorphTo
    {
        return $this->morphTo();
    }

    public function credentials(): HasMany
    {
        return $this->hasMany(
            IdentityCredential::class
        );
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}

```

---

## File: `src\Services\LoginPipeline.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Services\LoginPipeline.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Services;

use Illuminate\Support\Facades\Auth;
use Rimba\Identity\Managers\PipelineManager;
use Rimba\Identity\Models\IdentityProfile;

class LoginPipeline
{
    public function __construct(
        protected PipelineManager $pipeline
    ) {}

    public function login(
        IdentityProfile $profile,
        array $payload
    ): bool {

        if (! $profile->is_enabled) {
            return false;
        }

        if (
            ! $this->pipeline->verify(
                $profile,
                $payload
            )
        ) {
            return false;
        }

        Auth::guard(
            config('identity.guard')
        )->login(
            $profile->personable
        );

        return true;
    }
}

```

---

## File: `src\Traits\HasIdentityProfile.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\identity\src\Traits\HasIdentityProfile.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Identity\Traits;

use Rimba\Identity\Models\IdentityProfile;

trait HasIdentityProfile
{
    public function identityProfile()
    {
        return $this->morphOne(
            IdentityProfile::class,
            'personable'
        );
    }
}

```

---

