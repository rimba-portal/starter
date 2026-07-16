# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:00*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Can`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Can\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Bark/Can/src' => 'Rimba\Bark\Can',
        ],
    ],
];

```

---

## File: `src\CanServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Can\src\CanServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Can;

use Bites\Base\Services\BitesServiceProvider;

class CanServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

