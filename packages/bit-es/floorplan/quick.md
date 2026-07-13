# PHP Files Code Dump
*Generated on: 2026-07-13 15:51:21*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\floorplan`*

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
                ->url(route('filament.staff.pages.location'))
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

use App\Services\BitesServiceProvider;
use Bites\FloorPlan\Actions\DiscoverFloorPlan;

class FloorPlanServiceProvider extends BitesServiceProvider
{
    protected string $configFile =
        __DIR__.'/../config/bites.php';

    protected string $viewsPath =
        __DIR__.'/../resources/views';

    protected string $iconsPath =
        __DIR__.'/../resources/svg';

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

