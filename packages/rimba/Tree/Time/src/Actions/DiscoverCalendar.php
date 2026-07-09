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
