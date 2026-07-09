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
