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
