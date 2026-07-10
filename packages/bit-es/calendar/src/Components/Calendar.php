<?php

declare(strict_types=1);

namespace Bites\Calendar\Components;

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

class Calendar extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public $events;

    protected $view = 'bites::calendar';

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
                        collect(EventType::cases())->mapWithKeys(fn ($case): array => [$case->value => $case->getLabel()])->toArray()
                    ),
            ])
            ->recordActions([
                EditAction::make()
                    ->schema([
                        Forms\Components\Select::make('type')->label('Event Type')
                            ->options(collect(EventType::cases())->mapWithKeys(fn ($case): array => [$case->value => $case->getLabel()])->toArray())
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, Set $set): mixed => $state ? $set('color', EventType::from($state)->getColor()[300]) : $set('color', null)
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
                                ->options(collect(EventType::cases())->mapWithKeys(fn ($case): array => [$case->value => $case->getLabel()])->toArray())
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn ($state, Set $set): mixed => $state ? $set('color', EventType::from($state)->getColor()[300]) : $set('color', null)
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
                'title' => $event->title,
                'start' => optional($event->starts_at)->toDateString(),
                'color' => $event->color,
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
