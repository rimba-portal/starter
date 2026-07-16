# PHP Files Code Dump
*Generated on: 2026-07-16 16:30:58*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Bark\Branding`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Branding\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Bark/Branding/src' => 'Rimba\Bark\Branding',
        ],
    ],
];

```

---

## File: `src\BrandingServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Bark\Branding\src\BrandingServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Bark\Branding;

use Bites\Base\Services\BitesServiceProvider;

class BrandingServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

