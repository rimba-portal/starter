app/Providers/FilamentServiceProvider.php 
```php
<?php

namespace App\Providers;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('staff')
            ->path('staff')
            ->login()
            ->colors([
                'primary' => '#2563eb',
            ])
            ->discoverResources(in: app_path('Filament/Staff/Resources'), for: 'App\\Filament\\Staff\\Resources')
            ->discoverPages(in: app_path('Filament/Staff/Pages'), for: 'App\\Filament\\Staff\\Pages')
            ->discoverWidgets(in: app_path('Filament/Staff/Widgets'), for: 'App\\Filament\\Staff\\Widgets')
            ->renderHook(
                PanelsRenderHook::USER_MENU_AFTER,
                function (): View {
                    return view('filament.hooks.user-menu-calendar');
                }
            );
    }
}
```
resources/views/filament/hooks/user-menu-calendar.blade.php
```php
{{-- Divider line --}}
<div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

{{-- Calendar link in user menu --}}
<x-filament::dropdown.list.item
    icon="myicon-s-calendar"
    wire:click="$dispatch('open-modal', { id: 'calendar-slideover' })"
>
    {{ __('Calendar') }}
</x-filament::dropdown.list.item>

{{-- Slideover Modal --}}
@push('modals')
    <x-filament::modal
        id="calendar-slideover"
        slideover
        width="4xl"
        :heading="__('Calendar')"
        :subheading="__('Calendar view of workdays, holidays and events.')"
        class="overflow-y-auto"
    >
        @livewire(\App\Filament\Staff\Pages\Calendar::class)
    </x-filament::modal>
@endpush

```

app/Filament/Staff/Pages/Calendar.php

```php
<?php

declare(strict_types=1);

namespace App\Filament\Staff\Pages;

use App\Support\ShiftPattern;
use BackedEnum;
use Bites\Base\Todo\Enums\EventType;
use Bites\Base\Todo\Event;
use Bites\Service\Concerns\HasHelp;
use Carbon\Carbon;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
use Livewire\Component;
use UnitEnum;

class Calendar extends Component implements HasActions, HasForms, HasTable
{
    use HasHelp;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $events;

    protected $view = 'filament.staff.pages.calendar';

    public function table(Table $table): Table
    {
        return $table
            ->query(Event::query())
            ->paginated(['all'])
            ->columns([
                TextColumn::make('title')->label('Title'),
                TextColumn::make('description')->label('Description'),
                ColorColumn::make('color')->label('Event Color')->sortable(),
                TextColumn::make('starts_at')->date('D M j, Y')->label('Date')->sortable(),
            ])
            ->groups([
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
                        collect(EventType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])->toArray()
                    ),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Forms\Components\Select::make('type')->label('Event Type')
                            ->options(collect(EventType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])->toArray())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Set $set) =>
                                $state ? $set('color', EventType::from($state)->getColor()[300]) : $set('color', null)
                            ),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Event Color')
                            ->disabled()
                            ->dehydrated(),
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
                                ->options(collect(EventType::cases())->mapWithKeys(fn ($case) => [$case->value => $case->getLabel()])->toArray())
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Set $set) =>
                                    $state ? $set('color', EventType::from($state)->getColor()[300]) : $set('color', null)
                                ),
                            Forms\Components\ColorPicker::make('color')
                                ->label('Event Color')
                                ->disabled()
                                ->dehydrated(),
                        ]),
                    ]),
            ]);
    }

    public function render(): View
    {
        $paginator = $this->getTableRecords();

        $public_events = collect($paginator->items())->map(function (Event $event): array {
            return [
                'title'   => $event->title,
                'start'   => optional($event->starts_at)->toDateString(),
                'color'   => $event->color,
                'allDay'  => $event->is_all_day,
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
                    sprintf('shift_pattern.patterns.%s.timezone', $foundPattern->getPatternKey()),
                    config('app.timezone', 'Asia/Kuala_Lumpur')
                );

                $now = Carbon::now($tz);
                $start = $now->copy()->startOfMonth()->subDays(7);
                $end = $now->copy()->endOfMonth()->addDays(7);

                $shiftEvents = collect($foundPattern->eventsForTeamInRange($shiftGroup, $start, $end));
            }
        }

        $events = $shiftEvents->concat($public_events)->values();
        $this->events = $events->toJson();

        return view($this->view);
    }
}

```

resources/views/filament/staff/pages/calendar.blade.php

```php
<div class="space-y-6 p-1">
    {{-- FullCalendar --}}
    <x-filament::section>
        <div wire:ignore id="calendar" class="min-h-150 w-full"></div>
    </x-filament::section>

    {{-- Events Table --}}
    {{ $this->table }}
</div>

@assets
    <script src="{{ asset('js/rrule.min.js') }}"></script>
    <script src="{{ asset('js/calendar.min.js') }}"></script>
    <script src="{{ asset('js/index.global.min.js') }}"></script>
@endassets

@script
<script>
    let calendar;

    const initCalendar = () => {
        const calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;

        if (calendar) {
            calendar.destroy();
        }

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            weekNumbers: true,
            firstDay: 1,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'multiMonthYear,dayGridMonth,timeGridWeek'
            },
            height: 'auto',
            events: JSON.parse(@js($this->events)),
            eventDidMount: info => info.el.setAttribute('title', info.event.title || ''),
        });

        calendar.render();
    };

    document.addEventListener('DOMContentLoaded', initCalendar);
    document.addEventListener('livewire:navigated', initCalendar);
    document.addEventListener('filament:modal-opened', initCalendar);
    document.addEventListener('filament:modal-loaded', initCalendar);
</script>
@endscript

```
