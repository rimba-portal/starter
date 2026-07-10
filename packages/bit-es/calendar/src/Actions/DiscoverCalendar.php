<?php

declare(strict_types=1);

namespace Bites\Calendar\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Livewire\Livewire;
use Bites\Calendar\Actions\GetCalendarAction;
use Filament\Actions\Action;

class DiscoverCalendar
{
    public function execute(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn(): string => Action::make('Calendar')
                ->label('Calendar')
                ->iconButton()
                ->icon('bites-calendar')
                ->url(route('filament.staff.pages.calendar'))
                ->toHtml(),
        );
    }
}
