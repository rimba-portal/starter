<?php

declare(strict_types=1);

namespace Bites\Calendar\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class GetCalendarAction extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function calendarAction(): Action
    {
        return Action::make('calendarAction')
            ->icon('heroicon-o-calendar-days')
            // or your custom icon:
            // ->icon('bites-calendar')
            ->label('Calendar')
            ->iconButton()
            ->modalHeading('Calendar')
            ->modalDescription('Calendar view of workdays, holidays and events.')
            ->slideOver()
            ->modalWidth('7xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel('Close')
            ->schema([
                ViewField::make('calendar')
                    ->view('bites::calendar')
                    ->viewData([
                        'events' => $this->getCalendarEvents(),
                    ]),
            ]);
    }

    protected function getCalendarEvents(): array
    {
        return [
            [
                'title' => 'New Year\'s Day',
                'start' => '2026-01-01',
                'allDay' => true,
                'color' => '#22c55e',
            ],
            [
                'title' => 'Sample Task',
                'start' => now()->toDateString(),
                'allDay' => true,
                'color' => '#3b82f6',
            ],
        ];
    }

    public function render()
    {
        return view('bites::calendar-button');
    }
}