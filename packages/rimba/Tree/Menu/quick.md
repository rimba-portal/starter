# PHP Files Code Dump
*Generated on: 2026-07-16 16:31:10*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'rimba/Tree/Menu/src' => 'Rimba\Tree\Menu',
        ],
    ],
];

```

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

## File: `src\Enums\MenuCategory.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Enums\MenuCategory.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Enums;

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

## File: `src\Enums\MenuGroup.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Enums\MenuGroup.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Enums;

enum MenuGroup: string
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

## File: `src\Http\UI\Admin\Resources\Menus\MenuResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\MenuResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus;

use BackedEnum;
use Bites\Versioning\Traits\ResourceHasVersionRelations;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\CreateMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\EditMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\ListMenus;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages\ViewMenu;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas\MenuForm;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas\MenuInfolist;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Tables\MenusTable;
use Rimba\Tree\Menu\Models\Menu;

class MenuResource extends Resource
{
    use ResourceHasVersionRelations;

    protected static ?string $model = Menu::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return MenuForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return MenuInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
            'create' => CreateMenu::route('/create'),
            'view' => ViewMenu::route('/{record}'),
            'edit' => EditMenu::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Pages\CreateMenu.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Pages\CreateMenu.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\CreateRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Pages\EditMenu.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Pages\EditMenu.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Pages\ListMenus.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Pages\ListMenus.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;
use Rimba\Tree\Menu\Models\Menu;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Menu';

    protected ?string $subheading = 'Catalog of all company links.';

    public function getTabs(): array
    {
        $categories = Menu::query()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter() // remove nulls if needed
            ->toArray();

        $tabs = [];

        $tabs['all'] = Tab::make(); // default tab showing all records

        foreach ($categories as $category) {
            $tabs[$category] = Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category));
        }

        return $tabs;
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Pages\ViewMenu.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Pages\ViewMenu.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\MenuResource;

class ViewMenu extends ViewRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Schemas\MenuForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Schemas\MenuForm.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category')
                    ->required(),
                TextInput::make('group'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('description'),
                TextInput::make('icon'),
                TextInput::make('color'),
                TextInput::make('parent_id')
                    ->numeric(),
                TextInput::make('permission'),
                TextInput::make('panel'),
                Toggle::make('is_visible')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Schemas\MenuInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Schemas\MenuInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MenuInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category'),
                TextEntry::make('group')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->placeholder('-'),
                TextEntry::make('icon')
                    ->placeholder('-'),
                TextEntry::make('color')
                    ->placeholder('-'),
                TextEntry::make('parent_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('permission')
                    ->placeholder('-'),
                TextEntry::make('panel')
                    ->placeholder('-'),
                IconEntry::make('is_visible')
                    ->boolean(),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('sort')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Menus\Tables\MenusTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Admin\Resources\Menus\Tables\MenusTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Admin\Resources\Menus\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->searchable(),
                TextColumn::make('group')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('currentVersion')
                    ->label('Current Version')
                    ->getStateUsing(function ($record) {
                        // This safely checks your trait method and extracts the column you want
                        return $record->currentVersion()?->version_number ?? 'No Version';
                    }),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable(),
                TextColumn::make('icon')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                TextColumn::make('parent_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('permission')
                    ->searchable(),
                TextColumn::make('panel')
                    ->searchable(),
                IconColumn::make('is_visible')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('sort')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

```

---

## File: `src\Http\UI\Staff\Resources\Menus\MenuResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Staff\Resources\Menus\MenuResource.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\Pages\ListMenus;
use Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\Tables\MenusTable;
use Rimba\Tree\Menu\Models\Menu;
use UnitEnum;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static string|UnitEnum|null $navigationGroup = 'Catalog';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-menu';

    protected static ?string $navigationLabel = 'Menu';

    protected static ?int $navigationSort = 31;

    protected static ?string $recordTitleAttribute = 'title';

    public static function table(Table $table): Table
    {
        return MenusTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenus::route('/'),
        ];
    }
}

```

---

## File: `src\Http\UI\Staff\Resources\Menus\Pages\ListMenus.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Staff\Resources\Menus\Pages\ListMenus.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\Pages;

use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Rimba\Tree\Menu\Enums\MenuCategory;
use Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\MenuResource;
use Rimba\Tree\Menu\Models\Menu;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected static ?string $title = 'Menu';

    protected ?string $subheading = 'Catalog of all company links.';

    public function getTabs(): array
    {
        $tabs = [
            'all' => Tab::make('All'),
        ];

        foreach (MenuCategory::cases() as $category) {
            $tabs[$category->value] = Tab::make($category->label())
                ->icon($category->icon())
                ->modifyQueryUsing(fn ($query) => $query->where(
                    'category',
                    $category->value,
                ));
        }

        return $tabs;
    }

    // public function getTabs(): array
    // {
    //     $categories = Menu::query()
    //         ->select('category')
    //         ->distinct()
    //         ->pluck('category')
    //         ->filter() // remove nulls if needed
    //         ->toArray();

    //     $tabs = [];

    //     $tabs['all'] = Tab::make(); // default tab showing all records

    //     foreach ($categories as $category) {
    //         $tabs[$category] = Tab::make()
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('category', $category))
    //             ->icon('heroicon-m-user-group');
    //     }

    //     return $tabs;
    // }
}

```

---

## File: `src\Http\UI\Staff\Resources\Menus\Tables\MenusTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Menu\src\Http\UI\Staff\Resources\Menus\Tables\MenusTable.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Menu\Http\UI\Staff\Resources\Menus\Tables;

use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(\App\Filament\Core\Resources\Roles\Schemas\RoleCanView::tableVisibilityModifier(['su' => '153582']))
            ->columns([
                Split::make([
                    ImageColumn::make('icon')
                        ->label('')
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl('https://raw.githubusercontent.com/bit-ecosystem/bites/refs/heads/main/menu/business-idea.svg'), // to chanage to Str::kebab($record->title)
                    Stack::make([
                        TextColumn::make('name')
                            ->label('Name')
                            // ->searchable()
                            ->color('primary'),
                        TextColumn::make('description')
                            ->size(TextSize::ExtraSmall)
                            ->wrap(),
                    ]),
                ]),
            ])
            ->paginated(false)
            ->contentGrid([
                'md' => 2,
                'xl' => 4,
            ])
            ->recordUrl(
                fn (Model $model): string => $model->internal_link && Route::has($model->internal_link)
                    ? route($model->internal_link)
                    : ($model->external_link ?? '#')
            )
            ->filters([])
            ->toolbarActions([]);
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

use Bites\Base\Services\BitesServiceProvider;

class MenuServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

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

