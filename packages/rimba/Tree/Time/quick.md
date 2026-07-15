# PHP Files Code Dump
*Generated on: 2026-07-15 16:27:24*
*Target Folder: `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time`*

---

## File: `resources\views\calendar.blade.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\resources\views\calendar.blade.php`

```php
@php
    $calendarId = $calendarId ?? 'calendar-' . uniqid();
@endphp

<div>
    <div wire:ignore id="{{ $calendarId }}"></div>
    @assets
        <script src="{{ asset('js/rrule.min.js') }}"></script>
        <script src="{{ asset('js/calendar.min.js') }}"></script>
        <script src="{{ asset('js/index.global.min.js') }}"></script>
    @endassets

    @script
        <script>
            let calendar; // keep a reference to avoid duplicate inits

            const calendarFunction = () => {

                const calendarId = @js($calendarId);
                const events = @js($events);

                const initCalendar = () => {
                    const calendarEl = document.getElementById(calendarId);

                    if (!calendarEl) {
                        return;
                    }

                    if (calendarEl.dataset.calendarRendered === '1') {
                        return;
                    }

                    calendarEl.dataset.calendarRendered = '1';

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        weekNumbers: true,
                        firstDay: 1,

                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'multiMonthYear,dayGridMonth,timeGridWeek'
                        },

                        height: 650,

                        events: events,

                        eventDidMount: function(info) {
                            info.el.setAttribute('title', info.event.title || '');
                        },
                    });

                    calendar.render();
                };

                setTimeout(initCalendar, 150);

                document.addEventListener('livewire:navigated', () => {
                    setTimeout(initCalendar, 150);
                });
            }();
        </script>
    @endscript
</div>

```

---

## File: `src\Actions\CalendarSlideoverTrigger.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Actions\CalendarSlideoverTrigger.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Rimba\Tree\Time\Services\CalendarService;

class CalendarSlideoverTrigger extends Component implements HasActions
{
    use InteractsWithActions;

    public function calendarAction(): Action
    {
        return Action::make('calendar')
            ->icon('heroicon-o-calendar-days')
            ->iconButton()
            ->tooltip('Calendar')
            ->color('info')
            ->slideOver()
            ->modalWidth('screen')
            ->modalHeading('Calendar')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->modalContent(function (): View {
                return view('rimba::calendar', [
                    'calendarId' => 'calendar-slideover',
                    'events' => app(CalendarService::class)
                        ->getEventsForUser(Auth::user())
                        ->toArray(),
                ]);
            });
    }

    public function render(): View
    {
        return view('rimba::calendar-slideover');
    }
}

```

---

## File: `src\Actions\DiscoverCalendar.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Actions\DiscoverCalendar.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Livewire\Livewire;

class DiscoverCalendar
{
    public function execute(): void
    {
        Livewire::component(
            'rimba-tree-time-calendar-slideover',
            CalendarSlideoverTrigger::class
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn (): string => view('rimba::calendar-slideover')->render()
        );
    }
}

```

---

## File: `src\Actions\ViewCalendar.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Actions\ViewCalendar.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Actions;

use Filament\Actions\Action;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Rimba\Tree\Time\Services\CalendarService;

class GetCalendarAction extends Action
{
    public static function make(?string $name = 'calendar'): static
    {
        return parent::make($name)
            ->icon('heroicon-o-calendar')
            ->iconButton()
            ->tooltip('Calendar')
            ->slideOver()
            ->modalWidth('screen')
            ->modalContent(function (): Factory|View {
                return view('rimba::calendar', [
                    'calendarId' => 'calendar-slidemodel',
                    'events' => app(CalendarService::class)
                        ->getEventsForUser(Auth::user())
                        ->toArray(),
                ]);
            });
    }
}

```

---

## File: `src\Enums\EventType.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Enums\EventType.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Enums;

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

## File: `src\Models\Event.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Models\Event.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Models;

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
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Models\Shift.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Models;

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

## File: `src\Services\CalendarService.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Services\CalendarService.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Services;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Rimba\Tree\Time\Models\Event;
use Rimba\Tree\Time\Support\ShiftPattern;

class CalendarService
{
    public function getEventsForUser(?Authenticatable $user): Collection
    {
        $publicEvents = $this->getPublicEvents();

        $shiftEvents = $this->getShiftEvents($user);

        return $shiftEvents
            ->concat($publicEvents)
            ->values();
    }

    protected function getPublicEvents(): Collection
    {
        return Event::query()
            ->where('status', 'active')
            ->get()
            ->map(function (Event $event): array {
                return [
                    'title' => $event->title,
                    'start' => $event->starts_at?->toDateString(),
                    'end' => $event->ends_at?->toDateString(),
                    'allDay' => $event->is_all_day,
                    'color' => $event->event_color,
                ];
            });
    }

    protected function getShiftEvents(?Authenticatable $user): Collection
    {
        $scode = $user?->staff?->shiftCode;

        if (blank($scode)) {
            return collect();
        }

        [$shiftGroup] = explode('-', $scode, 2);

        foreach (array_keys(config('shift_pattern.patterns', [])) as $key) {
            $pattern = ShiftPattern::fromConfig($key);

            if (! $pattern->hasTeam($shiftGroup)) {
                continue;
            }

            $tz = config(
                sprintf('shift_pattern.patterns.%s.timezone', $key),
                config('app.timezone')
            );

            $now = Carbon::now($tz);

            return collect(
                $pattern->eventsForTeamInRange(
                    $shiftGroup,
                    $now->copy()->startOfMonth()->subDays(7),
                    $now->copy()->endOfMonth()->addDays(7),
                )
            );
        }

        return collect();
    }
}

```

---

## File: `src\Support\ShiftPattern.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\Support\ShiftPattern.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time\Support;

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

## File: `src\TimeServiceProvider.php`
**Absolute Path:** `C:\Users\153582\Herd\starter\packages\rimba\Tree\Time\src\TimeServiceProvider.php`

```php
<?php

declare(strict_types=1);

namespace Rimba\Tree\Time;

use Bites\Base\Services\BitesServiceProvider;
use Rimba\Tree\Time\Actions\DiscoverCalendar;

class TimeServiceProvider extends BitesServiceProvider
{
    protected function bootPackage(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'rimba');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // app(DiscoverCalendar::class)->execute();
    }
}

```

---

