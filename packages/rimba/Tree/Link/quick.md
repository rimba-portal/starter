# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:08*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Link`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Link\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Tree/Link/src' => 'Rimba\Tree\Link',
        ],
    ],
];

```

---

## File: `src\LinkServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Link\src\LinkServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Link;

use Bites\Base\Services\BitesServiceProvider;

class LinkServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected function bootPackage(): void
    {
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

