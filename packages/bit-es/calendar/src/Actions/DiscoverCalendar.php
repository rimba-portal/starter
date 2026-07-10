<?php

declare(strict_types=1);

namespace Bites\Calendar\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\Factory;
use Livewire\Livewire;
use Bites\Calendar\Actions\GetCalendarAction;

class DiscoverCalendar
{
    public function execute(): void
    {

        Livewire::component(
            'bites.calendar-action',
            GetCalendarAction::class,
        );


        FilamentView::registerRenderHook(
            PanelsRenderHook::SIMPLE_PAGE_END,
            fn(): string =>  \Illuminate\Support\Facades\Blade::render('@livewire(\'bites.calendar-button\')'),
        );
    }
}
