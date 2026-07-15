# PHP Files Code Dump
*Generated on: 2026-07-14 16:20:38*
*Target Folder: `C:\Users\153582\Herd\starter\packages\bit-es\calendar`*

---

## File: `config\bites.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\config\bites.php`

```php
<?php

declare(strict_types=1);

return [
    'ui' => [
        'packages' => [
            'calendar/src' => 'Bites\Calendar',
        ],
    ],
    'calendar' => [

        'default' => env('SHIFT_PATTERN_DEFAULT', 'WXYZ'),

        'patterns' => [
            'Normal' => [
                'anchor_date' => env('SHIFT_ANCHOR_NORMAL', '2026-01-05'), // Monday
                'timezone' => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),

                // 5 workdays, then 2 rest days
                'segments' => [
                    [
                        'len' => 5,
                        'code' => 'D',
                        'label' => '🐓',
                        'start' => '08:30',
                        'end' => '17:30',
                        'color' => '#22c55e',
                    ],
                    [
                        'len' => 2,
                        'code' => 'R', // Weekend rest (hidden)
                    ],
                ],

                'cycle_length' => 7,

                'teams' => [
                    '1' => [
                        'label' => 'Normal Hours',
                        'offset' => 0,
                        'color' => '#22c55e',
                    ],
                ],
            ],

            // === 24-day W/X/Y/Z pattern ===
            '4G3S' => [
                'anchor_date' => env('SHIFT_ANCHOR_WXYZ', '2026-01-15'),
                'timezone' => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),

                'segments' => [
                    ['len' => 6, 'code' => 'M', 'label' => '🐓', 'start' => '07:00', 'end' => '15:00', 'color' => '#90D5FF'],
                    ['len' => 2, 'code' => 'R'], // Rest (hidden)
                    ['len' => 6, 'code' => 'N', 'label' => '🦉', 'start' => '23:00', 'end' => '07:00(+1)', 'color' => '#B87333'],
                    ['len' => 2, 'code' => 'R'], // Rest (hidden)
                    ['len' => 6, 'code' => 'A', 'label' => '🦋', 'start' => '15:00', 'end' => '23:00', 'color' => '#CAF1DE'],
                    ['len' => 2, 'code' => 'R'], // Rest (hidden)
                ],

                'cycle_length' => 24,

                'teams' => [
                    'W' => ['label' => 'Team W', 'offset' => 22, 'color' => '#6b7280'],
                    'X' => ['label' => 'Team X', 'offset' => 10, 'color' => '#ef4444'],
                    'Y' => ['label' => 'Team Y', 'offset' => 4,  'color' => '#10b981'],
                    'Z' => ['label' => 'Team Z', 'offset' => 16, 'color' => '#3b82f6'],
                ],
            ],

            // === 12-day A/B/C pattern ===
            '3G2S' => [
                'anchor_date' => env('SHIFT_ANCHOR_ABC', '2026-01-07'),
                'timezone' => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),

                // 4N, 2R, 4M, 2R
                'segments' => [
                    ['len' => 4, 'code' => 'N', 'label' => '🦉',  'start' => '19:00', 'end' => '07:00(+1)', 'color' => '#B87333'],
                    ['len' => 2, 'code' => 'R'], // Rest (hidden)
                    ['len' => 4, 'code' => 'M', 'label' => '🐓', 'start' => '07:00', 'end' => '19:00',     'color' => '#90D5FF'],
                    ['len' => 2, 'code' => 'R'], // Rest (hidden)
                ],

                'cycle_length' => 12,

                'teams' => [
                    'A' => ['label' => 'Team A', 'offset' => 0,  'color' => '#0ea5e9'],
                    'B' => ['label' => 'Team B', 'offset' => 11, 'color' => '#f59e0b'],
                    'C' => ['label' => 'Team C', 'offset' => 7,  'color' => '#10b981'],
                ],
            ],
            '3G3S' => [
                'anchor_date' => '2026-03-01', // Sunday = Day 1 in the provided table
                'timezone' => env('APP_TIMEZONE', 'Asia/Kuala_Lumpur'),

                // Base segments: define the three timed shifts (morning/afternoon/night).
                // Days that should not show events (Rest, Off, Holiday) will be handled via overrides below.
                'segments' => [
                    ['len' => 1, 'code' => 'M', 'label' => '🐓',   'start' => '07:00',     'end' => '15:00',      'color' => '#90D5FF'],
                    ['len' => 1, 'code' => 'A', 'label' => '🦋', 'start' => '15:00',     'end' => '23:00',      'color' => '#CAF1DE'],
                    ['len' => 1, 'code' => 'N', 'label' => '🦉',     'start' => '23:00',     'end' => '07:00(+1)',  'color' => '#B87333'],
                ],
                // Cycle repeats M->A->N daily
                'cycle_length' => 3,

                // Teams D/E/F rotate through M/A/N by applying offsets.
                // Offsets chosen so that on 2026-03-02 (Mon) Morning=F, Afternoon=D, Night=E as in your table.
                'teams' => [
                    'D' => ['label' => 'Team D', 'offset' => 1, 'color' => '#0ea5e9'],
                    'E' => ['label' => 'Team E', 'offset' => 2, 'color' => '#f59e0b'],
                    'F' => ['label' => 'Team F', 'offset' => 0, 'color' => '#10b981'],
                ],

            ],
        ],
    ],
];

```

---

## File: `database\migrations\0002_01_01_000610_create_calendar_tables.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\database\migrations\0002_01_01_000610_create_calendar_tables.php`

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

        Schema::create('shifts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained();
            $table->foreignId('org_team_id')->nullable()->constrained();
            $table->foreignId('staff_id')->nullable()->constrained();
            $table->enum('type', ['fixed', 'rotational'])->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->json('attributes')->nullable();
            $table->timestamps();
        });
        Schema::create('events', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('org_unit_id')->nullable()->constrained('org_units')->nullOnDelete(); // Multi-team / org scoping
            $table->foreignId('owner_id')->nullable()->constrained('staff')->nullOnDelete(); // Ownership / organizer
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->boolean('is_all_day')->default(false);
            // Timezone handling:
            $table->dateTime('starts_at');       // local start time
            $table->dateTime('ends_at')->nullable(); // local end time
            $table->string('timezone', 64)->nullable();
            $table->dateTime('start_UTC')->nullable(); // for date-only queries (UTC date part of starts_at)
            $table->dateTime('end_UTC')->nullable();   // for date-only queries (UTC date part of ends_at)

            $table->string('type', 64)->nullable();     // your classify hierarchy can map here
            $table->string('status', 32)->default('planned'); // planned|tentative|confirmed|done|cancelled
            $table->string('color', 32)->nullable();
            $table->timestamps();

            // Indexing strategy
            $table->index('starts_at');
            $table->index('ends_at');
            $table->index(['type', 'status']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('shifts');
    }
};

```

---

## File: `resources\views\calendar.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\resources\views\calendar.blade.php`

```php
<x-filament-panels::page>
    <x-filament::section>
        <div wire:ignore id="calendar"></div>
    </x-filament::section>

    {{-- Page content --}}
    {{ $this->table }}

    @assets
    <script src="{{ asset('js/rrule.min.js') }}"></script>
    <script src="{{ asset('js/calendar.min.js') }}"></script>
    <script src="{{ asset('js/index.global.min.js') }}"></script>
    @endassets

    @script
    <script>
        let calendar; // keep a reference to avoid duplicate inits

        const calendarFunction = () => {
            const calendarEl = document.getElementById('calendar');
            if (!calendarEl) return;

            // If calendar already exists (e.g. on Livewire navigations), destroy it first
            if (calendar) {
                calendar.destroy();
            }

            calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                weekNumbers: true,
                firstDay: 1, // Start week on Monday
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'multiMonthYear,dayGridMonth,timeGridWeek'
                },
                height: 600,
                events: JSON.parse($wire.events),

                // ⬇️ Add this: show full title on hover
                eventDidMount: function(info) {
                    // Set the native title attribute for a simple tooltip
                    info.el.setAttribute('title', info.event.title || '');
                },
            });

            calendar.render();
        };

        // Initialize once DOM is ready
        document.addEventListener('DOMContentLoaded', calendarFunction);

        // Re-init when Filament/Livewire navigates
        document.addEventListener('livewire:navigated', calendarFunction);
    </script>
    @endscript
</x-filament-panels::page>
```

---

## File: `src\Actions\DiscoverCalendar.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Actions\DiscoverCalendar.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Actions;

use Filament\Actions\Action;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class DiscoverCalendar
{
    public function execute(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Action::make('Calendar')
                ->label('Calendar')
                ->iconButton()
                ->badge()
                ->icon('bites-calendar')
                ->url(route('filament.staff.pages.calendar'))
                ->toHtml(),
        );
    }
}

```

---

## File: `src\CalendarServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\CalendarServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar;

use Bites\Base\Services\BitesServiceProvider;
use Bites\Calendar\Actions\DiscoverCalendar;

class CalendarServiceProvider extends BitesServiceProvider
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
        app(DiscoverCalendar::class)->execute();
    }
}

```

---

## File: `src\Enums\EventType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Enums\EventType.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Enums;

use Filament\Support\Colors\Color;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum EventType: string implements HasDescription, HasLabel
{
    case PaidPublicHoliday = 'Paid Public Holiday';
    case UnpaidPublicHoliday = 'Unpaid Public Holiday';
    case InLieuRestDay = 'In-Lieu Rest Day';
    case CollectiveAnnualLeave = 'Collective Annual Leave';
    case SatOffDays = 'Saturday Off Days';
    case SatReplacementLeave = 'Saturday Replacement Leave';
    case ATMActivity = 'ATM Activity';
    case Others = 'Others';

    public function getLabel(): string|Htmlable|null
    {
        return $this->value;
    }

    public function getDescription(): string|Htmlable|null
    {
        return match ($this) {
            self::PaidPublicHoliday => 'This is a paid public holiday.',
            self::UnpaidPublicHoliday => 'This is an unpaid public holiday.',
            self::InLieuRestDay => 'This is an in-lieu rest day.',
            self::CollectiveAnnualLeave => 'This is collective annual leave.',
            self::SatOffDays => 'This is a Saturday off day for shift workers.',
            self::SatReplacementLeave => 'This is a Saturday replacement leave.',
            self::ATMActivity => 'This is an ATM activity day.',
            self::Others => 'This is an other event type.',

        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PaidPublicHoliday => Color::Orange,
            self::UnpaidPublicHoliday => Color::Red,
            self::CollectiveAnnualLeave => Color::Cyan,
            self::InLieuRestDay => Color::Yellow,
            self::SatOffDays => Color::Green,
            self::SatReplacementLeave => Color::Fuchsia,
            self::ATMActivity => Color::Blue,
            self::Others => Color::Zinc,
        };
    }

    /**
     * Return an OKLCH CSS string, e.g. "oklch(0.7021 0.1203 52.1349)".
     *
     * - If getColor() returns a Filament palette (array), uses $shade (default 500),
     *   then falls back to 500, then to the first available shade.
     * - If getColor() returns a string, assumes it's a hex color "#RRGGBB" or "RRGGBB".
     * - Returns null if no valid color can be resolved.
     */
    public function toOklch(int $shade = 500): ?string
    {
        $color = $this->getColor();

        if ($color === null) {
            return null;
        }

        // Resolve to a hex string
        $hex = null;

        if (is_array($color)) {
            // Filament palette: attempt requested shade, then 500, then first entry
            $candidate = $color[$shade] ?? ($color[500] ?? (is_array($color) ? reset($color) : null));
            if (is_string($candidate)) {
                $hex = $candidate;
            }
        } elseif (is_string($color)) {
            $hex = $color;
        }

        if (! is_string($hex)) {
            return null;
        }

        $hex = ltrim($hex, '#');
        if (! preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return null;
        }

        return self::hexToOklchCss($hex);
    }

    /**
     * Convert a hex string "RRGGBB" to an OKLCH CSS string "oklch(L C H)".
     * Based on the OKLab/OKLCH reference conversion.
     */
    private static function hexToOklchCss(string $hex): string
    {
        // Parse hex
        $ri = hexdec(substr($hex, 0, 2));
        $gi = hexdec(substr($hex, 2, 2));
        $bi = hexdec(substr($hex, 4, 2));

        $r = $ri / 255;
        $g = $gi / 255;
        $b = $bi / 255;

        // sRGB to linear
        $r = $r <= 0.04045 ? $r / 12.92 : pow(($r + 0.055) / 1.055, 2.4);
        $g = $g <= 0.04045 ? $g / 12.92 : pow(($g + 0.055) / 1.055, 2.4);
        $b = $b <= 0.04045 ? $b / 12.92 : pow(($b + 0.055) / 1.055, 2.4);

        // Linear RGB to LMS
        $l = 0.4122214708 * $r + 0.5363325363 * $g + 0.0514459929 * $b;
        $m = 0.2119034982 * $r + 0.6806995451 * $g + 0.1073969566 * $b;
        $s = 0.0883024619 * $r + 0.2817188376 * $g + 0.6299787005 * $b;

        // Nonlinear transform
        $l_ = self::cuberoot($l);
        $m_ = self::cuberoot($m);
        $s_ = self::cuberoot($s);

        // OKLab
        $L = 0.2104542553 * $l_ + 0.7936177850 * $m_ - 0.0040720468 * $s_;
        $a = 1.9779984951 * $l_ - 2.4285922050 * $m_ + 0.4505937099 * $s_;
        $b2 = 0.0259040371 * $l_ + 0.7827717662 * $m_ - 0.8086757660 * $s_;

        // OKLCH
        $C = sqrt($a * $a + $b2 * $b2);
        $h = rad2deg(atan2($b2, $a));
        if ($h < 0) {
            $h += 360;
        }

        return sprintf('oklch(%.4f %.4f %.4f)', $L, $C, $h);
    }

    private static function cuberoot(float $x): float
    {
        // Preserve sign for negatives (real cube root).
        return $x < 0 ? -pow(-$x, 1 / 3) : pow($x, 1 / 3);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\EventResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\EventResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events;

use BackedEnum;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\CreateEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\EditEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\ListEvents;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Pages\ViewEvent;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas\EventForm;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas\EventInfolist;
use Bites\Calendar\Http\UI\Admin\Resources\Events\Tables\EventsTable;
use Bites\Calendar\Models\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|UnitEnum|null $navigationGroup = 'Calendar';

    protected static string|BackedEnum|null $navigationIcon = 'bites-event';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
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
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'view' => ViewEvent::route('/{record}'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\Pages\CreateEvent.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Pages\CreateEvent.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Events\EventResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\Pages\EditEvent.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Pages\EditEvent.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Events\EventResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

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

## File: `src\Http\UI\Admin\Resources\Events\Pages\ListEvents.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Pages\ListEvents.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Events\EventResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\Pages\ViewEvent.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Pages\ViewEvent.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Events\EventResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEvent extends ViewRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\Schemas\EventForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Schemas\EventForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                TextInput::make('owner_id')
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_all_day')
                    ->required(),
                DateTimePicker::make('starts_at')
                    ->required(),
                DateTimePicker::make('ends_at'),
                TextInput::make('timezone'),
                DateTimePicker::make('start_UTC'),
                DateTimePicker::make('end_UTC'),
                TextInput::make('type'),
                TextInput::make('status')
                    ->required()
                    ->default('planned'),
                TextInput::make('color'),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Events\Schemas\EventInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Schemas\EventInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EventInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('orgUnit.name')
                    ->label('Org unit')
                    ->placeholder('-'),
                TextEntry::make('owner_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('is_all_day')
                    ->boolean(),
                TextEntry::make('starts_at')
                    ->dateTime(),
                TextEntry::make('ends_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('timezone')
                    ->placeholder('-'),
                TextEntry::make('start_UTC')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('end_UTC')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('color')
                    ->placeholder('-'),
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

## File: `src\Http\UI\Admin\Resources\Events\Tables\EventsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Events\Tables\EventsTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orgUnit.name')
                    ->searchable(),
                TextColumn::make('owner_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                IconColumn::make('is_all_day')
                    ->boolean(),
                TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('timezone')
                    ->searchable(),
                TextColumn::make('start_UTC')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_UTC')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
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

## File: `src\Http\UI\Admin\Resources\Shifts\Pages\CreateShift.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Pages\CreateShift.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShift extends CreateRecord
{
    protected static string $resource = ShiftResource::class;
}

```

---

## File: `src\Http\UI\Admin\Resources\Shifts\Pages\EditShift.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Pages\EditShift.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;

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

## File: `src\Http\UI\Admin\Resources\Shifts\Pages\ListShifts.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Pages\ListShifts.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Shifts\Pages\ViewShift.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Pages\ViewShift.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages;

use Bites\Calendar\Http\UI\Admin\Resources\Shifts\ShiftResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewShift extends ViewRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Shifts\Schemas\ShiftForm.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Schemas\ShiftForm.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('org_unit_id')
                    ->relationship('orgUnit', 'name'),
                Select::make('org_team_id')
                    ->relationship('orgTeam', 'name'),
                Select::make('staff_id')
                    ->relationship('staff', 'name'),
                TextInput::make('type'),
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TimePicker::make('start_time')
                    ->required(),
                TimePicker::make('end_time')
                    ->required(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Textarea::make('attributes')
                    ->columnSpanFull(),
            ]);
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Shifts\Schemas\ShiftInfolist.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Schemas\ShiftInfolist.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ShiftInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('orgUnit.name')
                    ->label('Org unit')
                    ->placeholder('-'),
                TextEntry::make('orgTeam.name')
                    ->label('Org team')
                    ->placeholder('-'),
                TextEntry::make('staff.name')
                    ->label('Staff')
                    ->placeholder('-'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('start_time')
                    ->time(),
                TextEntry::make('end_time')
                    ->time(),
                TextEntry::make('start_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('attributes')
                    ->placeholder('-')
                    ->columnSpanFull(),
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

## File: `src\Http\UI\Admin\Resources\Shifts\ShiftResource.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\ShiftResource.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts;

use BackedEnum;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages\CreateShift;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages\EditShift;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages\ListShifts;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Pages\ViewShift;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Schemas\ShiftForm;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Schemas\ShiftInfolist;
use Bites\Calendar\Http\UI\Admin\Resources\Shifts\Tables\ShiftsTable;
use Bites\Calendar\Models\Shift;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static string|UnitEnum|null $navigationGroup = 'Calendar';

    protected static string|BackedEnum|null $navigationIcon = 'bites-shift';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ShiftForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShiftInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftsTable::configure($table);
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
            'index' => ListShifts::route('/'),
            'create' => CreateShift::route('/create'),
            'view' => ViewShift::route('/{record}'),
            'edit' => EditShift::route('/{record}/edit'),
        ];
    }
}

```

---

## File: `src\Http\UI\Admin\Resources\Shifts\Tables\ShiftsTable.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Admin\Resources\Shifts\Tables\ShiftsTable.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Admin\Resources\Shifts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShiftsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('orgUnit.name')
                    ->searchable(),
                TextColumn::make('orgTeam.name')
                    ->searchable(),
                TextColumn::make('staff.name')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('start_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('end_time')
                    ->time()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
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

## File: `src\Http\UI\Staff\Pages\Calendar.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Http\UI\Staff\Pages\Calendar.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Http\UI\Staff\Pages;

use BackedEnum;
use Bites\Calendar\Enums\EventType;
use Bites\Calendar\Models\Event;
use Bites\Calendar\Services\ShiftPattern;
use Carbon\Carbon;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class Calendar extends Page implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string|UnitEnum|null $navigationGroup = 'ToDo';

    protected static string|BackedEnum|null $navigationIcon = 'rimba-s-calendar';

    protected static ?string $navigationLabel = 'Calendar';

    protected static ?int $navigationSort = 12;

    protected static ?string $title = 'Calendar';

    protected ?string $subheading = 'Calendar view of workdays, holidays and events.';

    protected string $view = 'bites.calendar';

    public $events;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Event::query()
            )
            ->paginated(['all'])
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('description')->label('Description'),
                // ColorColumn::make('color')->label('Event Color')->sortable(),

                ColorColumn::make('event_type_color')
                    ->label('Event Color')
                    ->state(fn (Event $record): ?string => $this->getEventColor($record)),
                TextColumn::make('starts_at')->date('D M j, Y')->label('Date')->sortable(),
            ])
            ->groups([
                // Group by Month/Year from starts_at
                Group::make('starts_at')
                    ->label('Month')
                    ->getTitleFromRecordUsing(fn (Event $record) => optional($record->starts_at)?->isoFormat('MMMM • YYYY') ?? 'No Date')
                    ->getKeyFromRecordUsing(fn (Event $record) => optional($record->starts_at)?->format('Y-m') ?? '0000-00')
                    ->collapsible(),

                Group::make('iso_week')
                    ->label('Week')
                    ->getTitleFromRecordUsing(fn (Event $record): string => $record->starts_at ? sprintf('%s • %s', $record->starts_at->format('W'), $record->starts_at->format('o')) : 'No Date')
                    ->getKeyFromRecordUsing(fn (Event $record) => optional($record->starts_at)?->format('Y-m') ?? '0000-00')
                    ->orderQueryUsing(fn (Builder $query, string $direction) => $query->orderBy('starts_at', $direction))
                    ->collapsible(),

            ])
            ->filters([
                SelectFilter::make('type')
                    ->multiple()
                    ->options(
                        collect(EventType::cases())->mapWithKeys(function ($case): array {
                            return [$case->value => $case->getLabel()];
                        })->toArray()
                    ),
            ])
            ->recordActions([

                EditAction::make()
                    ->schema([
                        Forms\Components\Select::make('type')->label('Event Type')
                            ->options(
                                collect(EventType::cases())->mapWithKeys(function ($case): array {
                                    return [$case->value => $case->getLabel()];
                                })->toArray()
                            )
                            ->required()
                            ->live() // make it reactive
                            ->afterStateUpdated(function ($state, Set $set): void {
                                if (blank($state)) {
                                    $set('color', null);

                                    return;
                                }

                                // Map the selected string value back to enum and set color
                                $set('color', EventType::from($state)->getColor()[300]);
                            }),
                        Forms\Components\ColorPicker::make('color')
                            ->placeholder(null)
                            ->label('Event Color')
                            ->disabled()     // read-only in UI
                            ->dehydrated(),  // still gets saved to the database
                    ]),
            ])
            ->toolbarActions([
                CreateAction::make()
                    ->model(Event::class)
                    ->createAnother(false)
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Title'),
                        Flex::make([
                            Forms\Components\DateTimePicker::make('starts_at')->label('Start Date'),
                            Forms\Components\DateTimePicker::make('ends_at')->label('End Date'),
                            Forms\Components\Select::make('type')->label('Event Type')
                                ->options(
                                    collect(EventType::cases())->mapWithKeys(function ($case): array {
                                        return [$case->value => $case->getLabel()];
                                    })->toArray()
                                )
                                ->required()
                                ->live() // make it reactive
                                ->afterStateUpdated(function ($state, Set $set): void {
                                    if (blank($state)) {
                                        $set('color', null);

                                        return;
                                    }

                                    // Map the selected string value back to enum and set color
                                    $set('color', EventType::from($state)->getColor()[300]);
                                }),
                            Forms\Components\ColorPicker::make('color')
                                ->placeholder(null)
                                ->label('Event Color')
                                ->disabled()     // read-only in UI
                                ->dehydrated(),  // still gets saved to the database
                        ]),
                    ]),
            ]);
    }

    protected function getEventColor(Event $event, int $shade = 300): ?string
    {
        return EventType::tryFrom($event->type)?->getColor()[$shade] ?? null;
    }

    public function render(): View
    {
        // Returns a LengthAwarePaginator of the *current page* after filters/search/sort
        $paginator = $this->getTableRecords();

        $public_events = collect($paginator->items())->map(function (Event $event): array {
            return [
                'title' => $event->title,
                'start' => $event->starts_at?->toIso8601String(),
                'end' => $event->ends_at?->toIso8601String(),
                'color' => $this->getEventColor($event),
                'allDay' => $event->is_all_day,
            ];
        })->values();

        $shiftEvents = collect();

        $scode = Auth::user()?->staff?->shiftCode;

        if (filled($scode)) {
            [$shiftGroup, $shiftPattern] = explode('-', $scode, 2);

            $patterns = array_keys(config('shift_pattern.patterns', []));
            $foundPattern = null;

            foreach ($patterns as $key) {
                $pattern = ShiftPattern::fromConfig($key);

                if ($pattern->hasTeam($shiftGroup)) {
                    $foundPattern = $pattern;
                    break;
                }
            }

            if ($foundPattern instanceof ShiftPattern) {
                $tz = config(
                    sprintf(
                        'shift_pattern.patterns.%s.timezone',
                        $foundPattern->getPatternKey()
                    ),
                    config('app.timezone', 'Asia/Kuala_Lumpur')
                );

                $now = Carbon::now($tz);
                $start = $now->copy()->startOfMonth()->subDays(7);
                $end = $now->copy()->endOfMonth()->addDays(7);

                $shiftEvents = collect(
                    $foundPattern->eventsForTeamInRange($shiftGroup, $start, $end)
                );
            }
        }

        $events = $shiftEvents->concat($public_events)->values();
        // dd($events->take(3)->toArray());
        $this->events = $events->toJson();

        // dd($this->getTableRecords());
        return parent::render();
    }
}

```

---

## File: `src\Models\Event.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Models\Event.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Models;

use App\Trees\Organization\Models\OrgCorp;
use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\OrgUnit;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'org_corp_id',
    'org_unit_id',
    'org_team_id',
    'type',
    'name',
    'description',
    'starts_at',
    'ends_at',
    'is_blocking',
    'attributes',
])]
class Event extends Model
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

            'org_corp_id' => 'integer',
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',

            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'start_UTC' => 'datetime',
            'end_UTC' => 'datetime',

            'is_all_day' => 'boolean',
            'is_blocking' => 'boolean',

            'attributes' => 'array',
        ];
    }

    public function orgCorp(): BelongsTo
    {
        return $this->belongsTo(OrgCorp::class);
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }
}

```

---

## File: `src\Models\Shift.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Models\Shift.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Models;

use App\Trees\Organization\Models\OrgTeam;
use App\Trees\Organization\Models\OrgUnit;
use App\Trees\Organization\Models\Staff;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'org_unit_id',
    'org_team_id',
    'staff_id',
    'type',
    'name',
    'description',
    'start_time',
    'end_time',
    'start_date',
    'end_date',
    'attributes',
])]
class Shift extends Model
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
            'org_unit_id' => 'integer',
            'org_team_id' => 'integer',
            'staff_id' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
            'attributes' => 'array',
        ];
    }

    public function orgUnit(): BelongsTo
    {
        return $this->belongsTo(OrgUnit::class);
    }

    public function orgTeam(): BelongsTo
    {
        return $this->belongsTo(OrgTeam::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }
}

```

---

## File: `src\Services\ShiftPattern.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\bit-es\calendar\src\Services\ShiftPattern.php`

```php
<?php

declare(strict_types=1);

namespace Bites\Calendar\Services;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use InvalidArgumentException;

class ShiftPattern
{
    protected string $patternKey;

    protected Carbon $anchorDate;

    protected string $timezone;

    protected array $teams;

    protected array $segments;

    protected int $cycleLength;

    public function __construct(
        string $patternKey,
        Carbon $anchorDate,
        string $timezone,
        array $teams,
        array $segments,
        int $cycleLength,

    ) {
        $this->patternKey = $patternKey;
        $this->anchorDate = $anchorDate->copy()->startOfDay();
        $this->timezone = $timezone;
        $this->teams = $teams;
        $this->segments = $segments;
        $this->cycleLength = $cycleLength;
        $this->anchorDate->setTimezone($this->timezone);
    }

    public static function fromConfig(string $patternKey): self
    {
        $patterns = config('shift_pattern.patterns', []);
        $patternKey = $patternKey ?: config('shift_pattern.default', 'WXYZ');

        if (! isset($patterns[$patternKey])) {
            throw new InvalidArgumentException('Unknown shift pattern: '.$patternKey);
        }

        $cfg = $patterns[$patternKey];

        return new self(
            patternKey: $patternKey,
            anchorDate: Carbon::parse($cfg['anchor_date']),
            timezone: $cfg['timezone'] ?? config('app.timezone', 'Asia/Kuala_Lumpur'),
            teams: $cfg['teams'] ?? [],
            segments: $cfg['segments'] ?? [],
            cycleLength: $cfg['cycle_length'] ?? 24,
        );
    }

    public function getPatternKey(): string
    {
        return $this->patternKey;
    }

    protected function getSegmentByCode(string $code): ?array
    {
        foreach ($this->segments as $segment) {
            if ($segment['code'] === $code) {
                return $segment;
            }
        }

        return null;
    }

    public function getShiftCode(string $team, Carbon $date): string
    {
        $team = strtoupper($team);
        if (! isset($this->teams[$team])) {
            throw new InvalidArgumentException(sprintf("Unknown team '%s' for pattern '%s'.", $team, $this->patternKey));
        }

        $date = $date->copy()->startOfDay()->setTimezone($this->timezone);

        $d = $this->anchorDate->diffInDays($date, false);
        $offset = (int) Arr::get($this->teams[$team], 'offset', 0);

        $t = ($d + $offset) % $this->cycleLength;
        if ($t < 0) {
            $t += $this->cycleLength;
        }

        $cursor = 0;
        foreach ($this->segments as $segment) {
            $len = (int) $segment['len'];
            if ($t >= $cursor && $t < $cursor + $len) {
                return $segment['code']; // 'M'|'A'|'N'|'R'
            }

            $cursor += $len;
        }

        return 'R'; // If not found, default to rest (won't be emitted as event)
    }

    public function getShiftLabel(string $team, Carbon $date): string
    {
        $seg = $this->getSegmentByCode($this->getShiftCode($team, $date));

        return $seg['label'] ?? 'Rest';
    }

    public function makeEventFor(string $team, Carbon $date): ?array
    {
        $team = strtoupper($team);
        $code = $this->getShiftCode($team, $date);
        $seg = $this->getSegmentByCode($code);
        if (! $seg) {
            return null;
        }

        if ($code === 'R') {
            return null;
        }

        // Timed event
        [$sH, $sM] = explode(':', $seg['start']);
        $endRaw = $seg['end'];                               // e.g., "07:00(+1)"
        $plusOne = str_ends_with($endRaw, '(+1)');
        $endTime = $plusOne ? str_replace('(+1)', '', $endRaw) : $endRaw;
        [$eH, $eM] = explode(':', $endTime);

        $start = $date->copy()->setTime((int) $sH, (int) $sM)->setTimezone($this->timezone);
        $end = $date->copy()->setTime((int) $eH, (int) $eM)->setTimezone($this->timezone);
        if ($plusOne) {
            $end->addDay();
        }

        $color = Arr::get($seg, 'color') ?? Arr::get($this->teams[$team], 'color');

        return [
            'title' => sprintf('%s', $seg['label']),
            'start' => $start->toIso8601String(),
            'end' => $start->copy()->addHour()->toIso8601String(),
            'allDay' => false,
            'color' => $color,
            'classNames' => ['team-'.$team, 'shift-'.$code, 'pat-'.$this->patternKey],
            'extendedProps' => ['shiftCode' => $code, 'team' => $team, 'pattern' => $this->patternKey],
        ];
    }

    public function eventsForTeamInRange(string $team, Carbon $start, Carbon $end): array
    {
        $events = [];
        $carbonPeriod = CarbonPeriod::create(
            $start->copy()->startOfDay()->setTimezone($this->timezone),
            $end->copy()->startOfDay()->setTimezone($this->timezone)
        );
        foreach ($carbonPeriod as $day) {
            if ($event = $this->makeEventFor($team, $day)) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /** Little helper to check if a team belongs to this pattern */
    public function hasTeam(string $team): bool
    {
        return array_key_exists(strtoupper($team), $this->teams);
    }
}

```

---

