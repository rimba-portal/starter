# PHP Files Code Dump
*Generated on: 2026-07-16 16:30:51*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\floorplan`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'bit-es/floorplan/src' => 'Bites\FloorPlan',
        ],
    ],
];

```

---

## File: `database\migrations\0002_01_01_000607_create_locations_table.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\database\migrations\0002_01_01_000607_create_locations_table.php`

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

        Schema::create('locations', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('locations');
            $table->foreignId('org_corp_id')->nullable()->constrained('org_corps');
            $table->string('name');
            $table->string('type');
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('location_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('location_id')->constrained();
            $table->enum('type', ['primary', 'secondary', 'temporary'])->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('attributes')->nullable();
            $table->morphs('assignable');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_assignments');
        Schema::dropIfExists('locations');
    }
};

```

---

## File: `resources\views\floor-plan-view.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\resources\views\floor-plan-view.blade.php`

```php
<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white select-none"
        @contextmenu.prevent
    >
        {{-- Viewer (same structure as pdf-view) --}}
        <div class="flex-1 overflow-auto bg-gray-100 flex items-start justify-center">
            <iframe
                srcdoc='
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <meta charset="utf-8">
                        <style>
                            html, body {
                                margin: 0;
                                padding: 0;
                                background: #f3f4f6;
                                overflow: auto;
                            }
                            img {
                                display: block;
                                margin: 24px auto;
                                max-width: none;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="{{ asset('images/floorplan_1.png') }}">
                    </body>
                    </html>
                '
                class="bg-white shadow"
                style="width: 100%; height: 500px;"
            ></iframe>
        </div>
    </div>
</x-dynamic-component>
```

---

## File: `resources\views\pdf-view.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\resources\views\pdf-view.blade.php`

```php
<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white select-none"
        @contextmenu.prevent
    >
        @if ($getState())

    

            {{-- Viewer --}}
            <div class="flex-1 overflow-auto bg-gray-100 flex items-start justify-center ">
                <iframe
                    src="{{ Storage::url($getState()) }}#toolbar=0"
                    class="bg-white shadow"
                    style="
                        width: 100%;
                        height: 500px;
                    "
                ></iframe>
            </div>

        @else
            <p class="p-4 text-gray-500">No PDF available.</p>
        @endif
    </div>
</x-dynamic-component>

```

---

## File: `resources\views\zoomable-view.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\resources\views\zoomable-view.blade.php`

```php
<x-dynamic-component
    :component="$getEntryWrapperView()"
    :entry="$entry">
    @php
    $file = $getState();

    $extension = strtolower(pathinfo($file ?? '', PATHINFO_EXTENSION));

    $isPdf = $extension === 'pdf';

    $isImage = in_array($extension, [
    'png',
    'jpg',
    'jpeg',
    'gif',
    'webp',
    'bmp',
    'svg',
    ]);

    $url = $file ? Storage::url($file) : null;
    @endphp

    <div
        class="w-full border border-gray-300 rounded-lg overflow-hidden flex flex-col bg-white"
        @contextmenu.prevent>
        @if ($file)

        {{-- Toolbar --}}
        <div class="flex items-center gap-2 p-2 border-b bg-gray-50">

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = Math.max(zoomMin, zoom - zoomStep)">
                -
            </button>

            <span
                class="text-sm font-medium min-w-17.5] text-center"
                x-text="Math.round(zoom * 100) + '%'"></span>

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = Math.min(zoomMax, zoom + zoomStep)">
                +
            </button>

            <button
                type="button"
                class="px-3 py-1 text-sm border rounded"
                @click="zoom = 1">
                Reset
            </button>
        </div>

        {{-- Viewer --}}
        <div
            class="overflow-auto bg-gray-100"
            style="height: 700px;"
            @wheel.ctrl.prevent="
                    zoom = Math.max(
                        zoomMin,
                        Math.min(
                            zoomMax,
                            zoom + ($event.deltaY < 0 ? zoomStep : -zoomStep)
                        )
                    )
                ">

            {{-- PDF --}}
            @if ($isPdf)
            <iframe
                src="{{ Storage::url($getState()) }}#toolbar=0"
                class="bg-white shadow"
                style="
                        width: 100%;
                        height: 500px;
                    "></iframe>


            {{-- IMAGE --}}
            @elseif ($isImage)
            <div class="inline-block min-w-full min-h-full p-8">
                <img
                    src="{{ Storage::url($getState()) }}"
                    alt="Preview"
                    class="max-w-none transition-transform duration-100 ease-out"
                    :style="`transform: scale(${zoom}); transform-origin: top center;`" />
            </div>
            {{-- UNSUPPORTED --}}
            @else

            <div class="p-8 text-center">
                <p class="text-gray-500">
                    Preview not available for this file type.
                </p>

                {{ $url }} target="_blank"
                class="text-primary-600 underline"
                >
                Download File
                </a>
            </div>

            @endif

        </div>

        @else

        <div class="p-4 text-gray-500">
            No attachment available.
        </div>

        @endif
    </div>
</x-dynamic-component>
```

---

## File: `src\Actions\DiscoverFloorPlan.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\src\Actions\DiscoverFloorPlan.php`

```php
<?php

declare(strict_types=1);

namespace Bites\FloorPlan\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class DiscoverFloorPlan
{
    public function execute(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Action::make('FloorPlan')
                ->label('Floor Plan')
                ->iconButton()
                ->badge()
                ->icon('bites-location')
                ->url(route('filament.staff.pages.floor-plan'))
                ->toHtml(),
        );
    }
}

```

---

## File: `src\FloorPlanServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\src\FloorPlanServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\FloorPlan;

use Bites\Base\Services\BitesServiceProvider;
use Bites\FloorPlan\Actions\DiscoverFloorPlan;

class FloorPlanServiceProvider extends BitesServiceProvider
{
    protected string $configFile = __DIR__.'/../config/bites.php';

    protected string $viewsPath = __DIR__.'/../resources/views';

    protected string $iconsPath = __DIR__.'/../resources/svg';

    protected function registerPackage(): void
    {
        //
    }

    protected function bootPackage(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        app(DiscoverFloorPlan::class)->execute();
    }
}

```

---

## File: `src\Http\UI\Staff\Pages\FloorPlan.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\src\Http\UI\Staff\Pages\FloorPlan.php`

```php
<?php

declare(strict_types=1);

namespace Bites\FloorPlan\Http\UI\Staff\Pages;

use BackedEnum;
use Bites\FloorPlan\Models\Location;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use UnitEnum;

class FloorPlan extends Page implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'Location';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-location';

    protected static ?string $navigationLabel = 'Floor Plan';

    protected static ?int $navigationSort = 51;

    protected static ?string $title = 'Floor Plan';

    protected ?string $subheading = 'Links to floor plans and maps of the organization buildings and campuses. Ideally includes registered storage locations.';

    protected string $view = 'staff.pages.location';

    public ?string $scope = 'all';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function locationInfolist(Schema $schema): Schema
    {
        return $schema
            // ->state(config('bites.emergency', []))
            ->schema([
                Section::make('1st Floor')
                    ->description('Hold Ctrl and scroll to zoom')
                    ->columnSpanFull()
                    ->schema([
                        ViewEntry::make('floor_plan')
                            // ->view('filament.infolists.components.floor-plan-view')
                            ->view('bites::floor-plan-view')
                            ->columnSpanFull(),

                    ])

                    ->collapsed(),

                // ========= GROUND FLOOR (height-based zoom) =========
                Section::make('Ground Floor')
                    ->extraAttributes([
                        // Alpine state lives here
                        'x-data' => '{
                    zoom: 1,
                    zoomMin: 0.5,
                    zoomMax: 3,
                    zoomStep: 0.1,}',
                        'id' => 'ground-floor-container',
                    ])
                // ->headerActions([
                //     Action::make('zoomIn')
                //         ->iconButton()
                //         ->icon('heroicon-m-magnifying-glass-plus')
                //         ->extraAttributes([
                //             // Directly change height (no custom event)
                //             '@click' => 'height = Math.min(max, height + step)',
                //         ]),

                //     Action::make('zoomOut')
                //         ->iconButton()
                //         ->icon('heroicon-m-magnifying-glass-minus')
                //         ->extraAttributes([
                //             '@click' => 'height = Math.max(min, height - step)',
                //         ]),

                //     Action::make('resetZoom')
                //         ->iconButton()
                //         ->icon('rimba-refresh2')
                //         ->tooltip('Reset zoom')
                //         ->extraAttributes([
                //             '@click' => 'height = 800',
                //         ]),
                // ])
                    ->schema([
                        ImageEntry::make('floor_plan_g')
                            ->hiddenLabel()
                            ->state(asset('images/floorplan_G.png'))
                            ->extraImgAttributes([
                                'class' => 'max-w-none transition-all duration-300 select-none',
                                'x-bind:style' => '`height: ${height}px`',
                            ])
                            ->extraAttributes([
                                'style' => 'max-height: 600px; overflow: auto;',
                                'class' => 'ring-1 ring-gray-200 rounded-lg bg-gray-50 p-2',
                            ]),
                    ])
                    ->columnSpanFull()
                    ->collapsed(),
                // ========= END GROUND FLOOR =========
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                return Location::query()
                    ->when($this->scope === 'rooms', fn ($q) => $q->where('type', 'room'))
                    ->when($this->scope === 'stores', fn ($q) => $q->where('type', 'store'))
                    ->when($this->scope === 'inactive', fn ($q) => $q->whereNotNull('ends_at'));
            })
            ->paginated(['all'])
            ->columns([
                TextColumn::make('code')->label('Code'),
                TextColumn::make('full_path')->label('Location Hierarchy'),
                TextColumn::make('description')->label('Description'),
            ])
            ->recordActions([
                //
            ])
            ->headerActions([
                Action::make('all')
                    ->label('All')
                    ->icon('heroicon-o-rectangle-stack')
                    ->color(fn (): string => $this->scope === 'all' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'all')
                    ->action(function (): void {
                        $this->scope = 'all';
                        $this->resetTablePage();
                    }),
                // ->badge(Location::count()),

                Action::make('rooms')
                    ->label('Rooms')
                    ->icon('heroicon-o-home-modern')
                    ->color(fn (): string => $this->scope === 'rooms' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'rooms')
                    ->action(function (): void {
                        $this->scope = 'rooms';
                        $this->resetTablePage();
                    }),
                // ->badge(Location::where('type', 'room')->count()),

                Action::make('stores')
                    ->label('Stores')
                    ->icon('heroicon-o-building-storefront')
                    ->color(fn (): string => $this->scope === 'stores' ? 'primary' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'stores')
                    ->action(function (): void {
                        $this->scope = 'stores';
                        $this->resetTablePage();
                    }),
                // ->badge(Location::where('type', 'store')->count()),

                Action::make('inactive')
                    ->label('Inactive')
                    ->icon('heroicon-o-archive-box')
                    ->color(fn (): string => $this->scope === 'inactive' ? 'warning' : 'gray')
                    ->outlined(fn (): bool => $this->scope !== 'inactive')
                    ->action(function (): void {
                        $this->scope = 'inactive';
                        $this->resetTablePage();
                    }),
                // ->badge(Location::whereNotNull('ends_at')->count()),
            ]);
    }
}

```

---

## File: `src\Models\Location.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\src\Models\Location.php`

```php
<?php

declare(strict_types=1);

namespace Bites\FloorPlan\Models;

use App\Trees\Organization\Models\OrgCorp;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'parent_id',
    'org_corp_id',
    'type',
    'name',
    'code',
    'description',
    'attributes',
])]
class Location extends Model
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
            'parent_id' => 'integer',
            'org_corp_id' => 'integer',
            'attributes' => 'array',
        ];
    }

    public function childrens(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function locationAssignments(): HasMany
    {
        return $this->hasMany(LocationAssignment::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }
}

```

---

## File: `src\Models\LocationAssignment.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\floorplan\src\Models\LocationAssignment.php`

```php
<?php

declare(strict_types=1);

namespace Bites\FloorPlan\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'location_id',
    'type',
    'start_date',
    'end_date',
    'attributes',
])]
class LocationAssignment extends Model
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
            'location_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}

```

---

