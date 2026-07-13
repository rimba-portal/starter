# PHP Files Code Dump
*Generated on: 2026-07-13 07:38:45*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu`*

---

## File: `database\migrations\0002_01_01_000103_create_menus_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\database\migrations\0002_01_01_000103_create_menus_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table): void {
            $table->id();

            // Enterprise taxonomy
            $table->string('category');          // Enterprise, People, Market, etc.
            $table->string('group')->nullable(); // Procurement, Payroll, Documents, etc.

            // Display
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();

            $table->string('icon')->nullable();
            $table->string('color')->nullable();

            // Navigation hierarchy
            $table->foreignId('parent_id')->nullable()->constrained('menus')->nullOnDelete();

            // Access
            $table->string('permission')->nullable();
            $table->string('panel')->nullable();

            // Behaviour
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_active')->default(true);

            // Ordering
            $table->unsignedInteger('sort')->default(0);

            $table->timestamps();

            $table->index('category');
            $table->index('group');
            $table->index('parent_id');
            $table->index('sort');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};

```

---

## File: `src\Enums\ContentType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Enums\ContentType.php`

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum ContentType: string
{
    case FilamentPage = 'filament-page';

    case FilamentResource = 'filament-resource';

    case Route = 'route';

    case Url = 'url';

    case Markdown = 'markdown';

    case Document = 'document';

    case Folder = 'folder';

    case Report = 'report';

    case Dashboard = 'dashboard';

    case Api = 'api';

    case File = 'file';

    case Video = 'video';

    case Html = 'html';

    public function label(): string
    {
        return ucwords(str_replace('-', ' ', $this->value));
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [
                $case->value => $case->label(),
            ])
            ->all();
    }
}

```

---

## File: `src\Enums\MenuCategory.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Enums\MenuCategory.php`

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum MenuCategory: string
{
    case Enterprise = 'enterprise';
    case People = 'people';
    case Market = 'market';
    case Supply = 'supply';
    case Operate = 'operate';
    case Technology = 'technology';
    case Knowledge = 'knowledge';
    case Source = 'source';

    public function label(): string
    {
        return match ($this) {
            self::Enterprise => 'Enterprise',
            self::People => 'People',
            self::Market => 'Market',
            self::Supply => 'Supply',
            self::Operate => 'Operate',
            self::Technology => 'Technology',
            self::Knowledge => 'Knowledge',
            self::Source => 'Source',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Enterprise => 'heroicon-o-building-office',
            self::People => 'heroicon-o-users',
            self::Market => 'heroicon-o-shopping-bag',
            self::Supply => 'heroicon-o-truck',
            self::Operate => 'heroicon-o-cog-6-tooth',
            self::Technology => 'heroicon-o-cpu-chip',
            self::Knowledge => 'heroicon-o-book-open',
            self::Source => 'heroicon-o-circle-stack',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [
                $case->value => $case->label(),
            ])
            ->all();
    }
}

```

---

## File: `src\Enums\VersionStatus.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Enums\VersionStatus.php`

```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum VersionStatus: string
{
    case Draft = 'draft';

    case Review = 'review';

    case Released = 'released';

    case Deprecated = 'deprecated';

    case Archived = 'archived';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Review => 'warning',
            self::Released => 'success',
            self::Deprecated => 'danger',
            self::Archived => 'gray',
        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $case): array => [
                $case->value => $case->label(),
            ])
            ->all();
    }
}

```

---

## File: `src\MenuServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\MenuServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu;

use App\Services\BitesServiceProvider;

class MenuServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}

```

---

## File: `src\Models\Menu.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Models\Menu.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Models;

use Bites\Service\Concerns\HasAttachableExtLink;
use Bites\Versioning\Traits\HasVersions;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'category',
    'group',
    'name',
    'slug',
    'description',
    'icon',
    'color',
    'parent_id',
    'permission',
    'panel',

    'is_visible',
    'is_active',
    'sort',
])]
class Menu extends Model
{
    // use HasAttachableExtLink;
    use HasVersions;

    protected $guard_name = 'web';

    protected $attributes = [
        'is_active' => false,
    ];
}

```

---

## File: `src\Services\MenuResolver.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Services\MenuResolver.php`

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ContentType;
use App\Models\Menu;
use App\Models\Version;
use Illuminate\Http\RedirectResponse;

class MenuResolver
{
    public function resolve(Menu $menu): RedirectResponse|string|null
    {
        /** @var Version|null $version */
        $version = $menu->activeVersion;

        if (! $version) {
            return null;
        }

        return $this->resolveVersion($version);
    }

    public function resolveVersion(Version $version): RedirectResponse|string|null
    {
        return match (ContentType::from($version->content_type)) {

            ContentType::Route => redirect()->route($version->target),

            ContentType::Url => redirect()->away($version->target),

            ContentType::FilamentPage => redirect()->route($version->target),

            ContentType::FilamentResource => redirect()->route($version->target),

            ContentType::Dashboard => redirect()->route($version->target),

            ContentType::Report => redirect()->route($version->target),

            ContentType::Document,
            ContentType::Folder,
            ContentType::Markdown,
            ContentType::File,
            ContentType::Api,
            ContentType::Video,
            ContentType::Html => $version->target,

        };
    }
}

```

---

